<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"> 
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <style>
      .container-wrapper {
        width: 100%;
        min-height: 100vh;
        background-color: #f3f4f6;
        display: flex;
        justify-content: center;
        /* align-items: center; */
        padding: 1rem;
      }
      .email-card {
        max-width: 600px;
        margin: 50px 0;
        width: 100%;
        background-color: white;
        padding: 2rem;
        border-radius: 0.5rem;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
      }
      .header {
        text-align: center;
        margin-bottom: 2rem;
      }
      .title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
      }
      .subtitle {
        color: #4b5563;
      }
      .verification-box {
        background-color: #f9fafb;
        padding: 2rem 1rem;
        border-radius: 0.5rem;
        margin-bottom: 2rem;
      }
      .verification-guide {
        font-size: 0.875rem;
        color: #4b5563;
        text-align: center;
        margin-bottom: 0.75rem;
      }
      .code-container {
        text-align: center;
      }
      .verification-code {
        font-size: 2.25rem;
        font-weight: 700;
        letter-spacing: 0.5em;
        color: #1f2937;
      }
      .info-section {
        display: flex;
        flex-direction: column;
        gap: 1rem;
      }
      .time-warning {
        color: #ef4444;
        font-size: 0.875rem;
        text-align: center;
      }
      .notice-list {
        color: #6b7280;
        font-size: 0.875rem;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
      }
      .footer {
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e5e7eb;
        text-align: center;
      }
      .footer-text {
        color: #9ca3af;
        font-size: 0.875rem;
      }
    </style>
  </head>
  <body>
    <div class="container-wrapper">
      <div class="email-card">
        <div class="header">
          <h1 class="title">이메일 인증</h1>
          <p class="subtitle">
            안녕하세요. 서비스 이용을 위한 이메일 인증을 진행해 주세요.
          </p>
        </div>
        <div class="verification-box">
          <p class="verification-guide">아래의 인증번호를 입력해주세요</p>
          <div class="code-container">
            <span class="verification-code">
              {{ $code }}
            </span>
          </div>
        </div>
        <div class="info-section">
          <p class="time-warning">인증번호는 5분 동안만 유효합니다.</p>
          <div class="notice-list">
            <p>• 인증번호는 1회만 사용 가능합니다.</p>
            <p>
              • 인증번호 재발급이 필요한 경우 이메일 인증을 다시 요청해 주세요.
            </p>
            <p>• 본 메일은 발신전용이며, 회신이 되지 않습니다.</p>
          </div>
        </div>
        <div class="footer">
          <p class="footer-text">
            본 이메일은 회원님의 이메일 인증 요청으로 발송되었습니다.
          </p>
        </div>
      </div>
    </div>
  </body>
</html>