<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;
use Aws\S3\S3Client;


/**
 * @group User
 * Operations related to users.
 */
class UserController extends Controller
{

    /**
     * 使用者個人資訊
     */
    public function show()
    {

        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $userData = User::select('name', 'photo', 'email')->where('id', $user->id)->first();

        return response()->json($userData);
    }

    /**
     * 更新使用者個人資訊並上傳圖片
     */

    public function update(UserRequest $request)
    {

        $user = JWTAuth::parseToken()->authenticate();
        $validatedData = $request->validated();


        // 更新其他已驗證的請求數據
        $user->update($validatedData);

        // 如果上傳了檔案，生成 presigned URL
        if ($request->hasFile('userPhoto')) {
            $file = $request->file('userPhoto');
            $filename = time() . '.' . $file->extension();
            $filetype = $file->getClientMimeType();

            //產生Ｓ３client物件
            $s3Client = $this->createS3Client();

            // 取得路物件
            // 設定Ｓ3檔案路徑
            $filePath = 'userPhotos/' . $filename;

            // 這是要存到資料庫的Ｓ3 URL
            $baseS3Url = config('filesystems.disks.s3.base_s3_url');

            // 最終此上傳圖片的ＵＲＬ
            $fullS3Url = $baseS3Url . $filePath;

            // 產生presignedUrl
            $presignedUrl = $this->generatePresignedUrl($s3Client, $filePath, $filetype);

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


    public function createS3Client()
    {
        return new S3Client([
            'version' => 'latest',
            'region' => config('filesystems.disks.s3.region'),
            'credentials' => [
                'key' => config('filesystems.disks.s3.key'),
                'secret' => config('filesystems.disks.s3.secret'),
            ],
        ]);
    }

    public function generatePresignedUrl(S3Client $s3Client, $filePath, $filetype)
    {
        $cmd = $s3Client->getCommand('PutObject', [
            'Bucket' => config('filesystems.disks.s3.bucket'),
            'Key'    => $filePath,
            'ContentType' => $filetype,
        ]);

        $requestObj = $s3Client->createPresignedRequest($cmd, '+10 minutes');
        return (string) $requestObj->getUri();
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
