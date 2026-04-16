import axios from 'axios';

const apiBaseURL = import.meta.env.VITE_API_BASE_URL?.trim() || '/api';

const api = axios.create({
    baseURL: apiBaseURL,
    timeout: 10000,

});

api.interceptors.request.use((config) =>{
    const token = localStorage.getItem('token');
    if(token){
        config.headers.Authorization = `Bearer ${token}`
    }
    return config;
})



export default api;

export function getApiErrorMessage(error, fallback = "Something went wrong. Please try again.") {
    if (error?.code === "ECONNABORTED") {
        return "Request timeout. Check if backend server is running.";
    }

    if (!error?.response) {
        return "Cannot reach API server. Verify Laravel is running and API URL is correct.";
    }

    const data = error.response.data;

    if (typeof data?.message === "string" && data.message.trim()) {
        if (data?.errors && typeof data.errors === "object") {
            const firstField = Object.keys(data.errors)[0];
            const firstError = data.errors[firstField]?.[0];
            if (firstError) {
                return `${data.message}: ${firstError}`;
            }
        }
        return data.message;
    }

    return fallback;
}
