import axios from "axios";

const axiosInstance = axios.create({
    // 기본 URL 설정 방법
    // 자동으로 설정된 값 말고 내가 원하는 값을 설정하고 싶을 때 사용
    // baseURL: '112.222.157.156:6525',

    // 기본 header 설정 방법
    headers: {
        'Content-Type': 'application/json',
    }
});

export default axiosInstance;