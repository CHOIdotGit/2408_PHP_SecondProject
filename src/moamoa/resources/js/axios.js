import axios from "axios";

const axiosInstance = axios.create({
    // 기본 URL 설정 방법
    // 자동으로 설정된 값 말고 내가 원하는 값을 설정하고 싶을 때 사용
    // baseURL: '112.222.157.156:6525',

    withCredentials : true,  // 세션 쿠키를 포함하여 요청을 보냄
    withXSRFToken  : true, // CSRF 토큰을 포함하여 요청을 보냄

    // 기본 header 설정 방법
    headers: {
        'Content-Type': 'application/json',
    }
});

// 새 CSRF 토큰 발행 함수
function fetchCsrfToken() {
    axios.get('/api/csrf-token')
    .then(res => {
        return res.data.csrf_token;
    });
}

// 세션 전송용 CSRF 토큰 설정 
axiosInstance.interceptors.request.use(
    (config) => {
        // welcome 블레이드에 세팅헤놓은 meta 태그에서 CSRF 토큰을 가져오기
        // document.querySelector('meta[name="csrf-token"]').setAttribute('content', csrfToken);
        // const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // csrf 토큰 발행
        const csrfToken = fetchCsrfToken();

        if(csrfToken) {
            config.headers.common['X-CSRF-TOKEN'] = csrfToken; // CSRF 토큰을 헤더에 추가
        }

        return config;
    },
    (err) => {
        return Promise.reject(err);
    }
);

export default axiosInstance;