import axios from "axios";
import { BASE_URL, TOKEN } from "../constant";
import { getCookie } from "@/helper";

const apiClient = axios.create({
    baseURL: BASE_URL,
});

apiClient.interceptors.request.use(
    (config) => {
        if (config.data instanceof FormData) {
            config.headers["Content-Type"] = "multipart/form-data";
        } else {
            config.headers["Content-Type"] = "application/json";
        }

        config.headers["Accept"] = "application/json";

        const token = getCookie(TOKEN) || null;
        if (token) {
            config.headers["Authorization"] = `Bearer ${token}`;
        }

        return config;
    },
    (error) => {
        return Promise.reject(error);
    },
);

export { apiClient };
