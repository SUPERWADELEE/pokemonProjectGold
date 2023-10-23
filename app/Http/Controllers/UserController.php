<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;
use Aws\S3\S3Client;

class UserController extends Controller
{

    public function show()
    {

        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $userData = User::select('name', 'photo', 'email')->where('id', $user->id)->first();

        return response()->json($userData);
    }

    public function update(Request $request)
    {

        $user = JWTAuth::parseToken()->authenticate();
        // 驗證上傳的檔案以及其他輸入字段...
        // ...
        $validatedData = $request->validate([
            'userPhoto' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // "sometimes" 代表此欄位為可選
            'name' => 'sometimes|required|max:255', // "sometimes" 代表此欄位為可選
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id // "sometimes" 代表此欄位為可選
        ]);
        // 更新其他已驗證的請求數據
        $user->update($validatedData);

        // 初始化回應數據
        $responseData = ['message' => 'Profile updated successfully!'];

        // 如果上傳了檔案，生成 presigned URL
        if ($request->hasFile('userPhoto')) {
            $file = $request->file('userPhoto');
            $filename = time() . '.' . $file->extension();
            $filetype = $file->getClientMimeType();

            // 
            $s3Client = new S3Client([
                'version' => 'latest',
                'region' => env('AWS_DEFAULT_REGION'),
                'credentials' => [
                    'key'    => env('AWS_ACCESS_KEY_ID'),
                    'secret' => env('AWS_SECRET_ACCESS_KEY'),
                ]
            ]);

            // 取得路徑x
            // 設定Ｓ3檔案路徑
            $filePath = 'userPhotos/' . $filename;

            // 這是要存到資料庫的Ｓ3 URL
            $baseS3Url = config('filesystems.disks.s3.base_s3_url');

            // 最終此上傳圖片的ＵＲＬ
            $fullS3Url = $baseS3Url . $filePath;

            //    定義presigned URL
            $cmd = $s3Client->getCommand('PutObject', [
                'Bucket' => env('AWS_BUCKET'),
                'Key'    => $filePath,
                // 'ACL'    => 'public-read',
                'ContentType' => $filetype,
            ]);

            // 使用 AWS SDK 來生成 presigned URL
            $requestObj = $s3Client->createPresignedRequest($cmd, '+10 minutes');
            $presignedUrl = (string) $requestObj->getUri();

            // 這個URL會給前端拿來當成ＵＲＬ
            $responseData['presignedUrl'] = $presignedUrl;

            // 這是給S3上面用的路徑
            $responseData['fileDestination'] = $filePath;


            // 儲存Ｓ3檔案路徑，讓前端可以根據這個路徑去讀取圖片檔案
            $user->photo = $fullS3Url;
            $user->save();

            // 注意：這邊你應該將 $responseData 回傳給前端
            return response()->json($responseData);
        }
    }

    // 超級使用者更改權限功能
    public function changeUserStatus(User $user)
    {
        // 在policy的部分定義了只有當前是超級使用者才可以操作
        $this->authorize('changePermission', $user);

        // 當狀態為0的時候改成1當狀態為1的時候改成0
        $status = $user->status;
        // if ($status == 0) { 
        // $user->update(['status' => 1]);
        // return response()->json(['message' => 'User role updated successfully']);
        // }

        $user->update(['status' => !$status]);
        return response()->json(['message' => 'User role updated successfully']);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
            // 'new_password_confirmation' => 'required|min:6'
        ]);

        $user = Auth::user();

        // 確認輸入的密碼是否與資料庫裡的相同
        $checkedPassword = Hash::check($request->current_password, $user->password);
        if (!$checkedPassword) {
            return response()->json(['error' => 'Current password is incorrect'], 400);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Password changed successfully']);
    }
}
