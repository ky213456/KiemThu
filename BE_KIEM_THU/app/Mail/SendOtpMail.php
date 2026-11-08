<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendOtpMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $hoTen;
    public $email;
    public $otp;

    /**
     * Create a new message instance.
     */
    public function __construct($hoTen, $email, $otp)
    {
        $this->hoTen = $hoTen;
        $this->email = $email;
        $this->otp   = $otp;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[Maison Atelier] Mã OTP Khôi Phục Mật Khẩu: ' . $this->otp,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            htmlString: "
                <div style='font-family: Arial, sans-serif; max-width: 520px; margin: 0 auto; padding: 28px; border: 1px solid #e2e8f0; border-radius: 14px; background: #ffffff; box-shadow: 0 4px 12px rgba(0,0,0,0.05);'>
                    <div style='text-align: center; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid #f1f5f9;'>
                        <h2 style='color: #0f1422; margin: 0; font-size: 22px; letter-spacing: 2px;'>✦ MAISON ATELIER ✦</h2>
                        <p style='color: #8b6b4e; font-size: 11px; margin-top: 4px; font-weight: bold; letter-spacing: 1.5px;'>BOUTIQUE OPERATIONS & FLAGSHIP PORTAL</p>
                    </div>
                    
                    <p style='color: #1e293b; font-size: 14.5px;'>Xin chào <strong>{$this->hoTen}</strong>,</p>
                    
                    <p style='color: #475569; font-size: 13.5px; line-height: 1.6;'>
                        Hệ thống Maison Atelier nhận được yêu cầu khôi phục quyền truy cập cho tài khoản của bạn: 
                        <span style='color: #0284c7; font-weight: 600;'>{$this->email}</span>.
                    </p>
                    
                    <p style='color: #475569; font-size: 13.5px;'>Dưới đây là mã xác thực OTP 6 chữ số của bạn:</p>
                    
                    <div style='text-align: center; margin: 28px 0;'>
                        <div style='display: inline-block; font-size: 34px; font-weight: 800; letter-spacing: 8px; color: #0f1422; background: #f8fafc; padding: 16px 32px; border-radius: 10px; border: 2px dashed #0f1422;'>
                            {$this->otp}
                        </div>
                    </div>
                    
                    <div style='background: #fffbeb; border: 1px solid #fef3c7; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;'>
                        <p style='color: #92400e; font-size: 12.5px; margin: 0; line-height: 1.5;'>
                            ⚠️ <strong>Lưu ý bảo mật:</strong> Mã xác thực OTP này chỉ có hiệu lực trong vòng <strong>15 phút</strong>. Tuyệt đối không chia sẻ mã này cho bất kỳ ai để đảm bảo an toàn tài khoản.
                        </p>
                    </div>
                    
                    <p style='color: #64748b; font-size: 12.5px; line-height: 1.5;'>
                        Nếu bạn không thực hiện yêu cầu này, vui lòng bỏ qua email hoặc liên hệ với bộ phận Quản trị viên để được hỗ trợ.
                    </p>
                    
                    <hr style='border: none; border-top: 1px solid #f1f5f9; margin: 24px 0;' />
                    
                    <div style='text-align: center;'>
                        <p style='color: #94a3b8; font-size: 11.5px; margin: 0;'>
                            Maison Atelier Flagship - Cổng Quản Trị & Khách Hàng VIP<br>
                            Hotline CSKH: 1900 8888 • Email: support@atelier.vn
                        </p>
                    </div>
                </div>
            ",
        );
    }
}
