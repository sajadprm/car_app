<?php
namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class RegisterController extends Controller  
{


/**
 * @OA\POST(
 *   path="/api/register",
 *   summary="Register user",
 *   tags={"User"},
 *   @OA\RequestBody(
 *     required=true,
 *     @OA\MediaType(
 *       mediaType="application/json",
 *       @OA\Schema(
 *         required={"mobile", "national_code"},
 *         @OA\Property(
 *           property="mobile",
 *           type="string",
 *           example="09123456789"
 *         ),
 *         @OA\Property(
 *           property="national_code",
 *           type="string",
 *           example="1234567890"
 *         )
 *       )
 *     )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="User registered successfully"
 *   ),
 *   @OA\Response(
 *     response=400,
 *     description="Invalid input"
 *   )
 * )
 */
    public function register(Request $request)
    {
        $validator = FacadesValidator::make($request->all(), [
            'mobile' => 'required|starts_with:09|size:11|unique:users,mobile',
            'national_code' =>'required|unique:users,national_code|size:10',
            
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }       

        $input = $request->all();
        $user = User::create($input);
        $success['token'] = $user->createToken('CarApp')->plainTextToken; 
        $success['mobile'] = $user->mobile;
        $success['national_code'] = $user->national_code;

        return $this->sendResponse($success, 'User register successfully.');
    }

/**
 * @OA\POST(
 *   path="/api/login",
 *   summary="Login user",
 *   tags={"User"},
 *   @OA\RequestBody(
 *     required=true,
 *     @OA\MediaType(
 *       mediaType="application/json",
 *       @OA\Schema(
 *         required={"mobile", "otp"},
 *         @OA\Property(
 *           property="mobile",
 *           type="string",
 *           example="09123456789"
 *         ),
 *         @OA\Property(
 *           property="otp",
 *           type="string",
 *           example="123456"
 *         )
 *       )
 *     )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="User logged in successfully"
 *   ),
 *   @OA\Response(
 *     response=400,
 *     description="Invalid input"
 *   )
 * )
 */

    public function login(Request $request)
    {
        // اعتبارسنجی ورودی‌ها
        $validator = FacadesValidator::make($request->all(), [
            'mobile' => 'required|starts_with:09|size:11',
            'otp' => 'required|size:6', // فرض کنید کد OTP یک کد ۶ رقمی است
        ]);
    
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
    
        // پیدا کردن کاربر با شماره موبایل
        $user = User::where('mobile', $request->mobile)->first();
    
        if (!$user) {
            return $this->sendError('User not found.', ['error' => 'User not found']);
        }
    
        // بررسی صحت کد OTP
        if ($request->otp === $user->otp) { // فرض کنید کد OTP در دیتابیس ذخیره شده
            // ایجاد توکن برای کاربر
            $success['token'] = $user->createToken('CarApp')->plainTextToken; 
            $success['mobile'] = $user->mobile;
    
            return $this->sendResponse($success, 'User login successfully.');
        } else {
            return $this->sendError('Invalid OTP.', ['error' => 'Invalid OTP']);
        }
    }
    
}
