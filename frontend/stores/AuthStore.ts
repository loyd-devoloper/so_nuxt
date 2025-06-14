import { useAxiosDefaultStore } from "~/stores/AxiosDefault";
import type { CredentialType, SchoolCredentialType } from "~/shared/types/LoginType";
import type { RouteParamValue } from "#vue-router";
import { AccountRoleEnum } from "#shared/enums/AccountRoleEnum";


export const useAuthStore = defineStore('authStore', () => {
    const authUser = ref<{
        [key: string]: any;
    }>({});
    const logging_out = ref<boolean>(false)
    const axiosDefault = useAxiosDefaultStore();

    const schoolLogin = async (credential: SchoolCredentialType) => {
        try {
            await axiosDefault.guestAxiosInstance().get(`/sanctum/csrf-cookie`);
            const response = await axiosDefault.guestAxiosInstance().post(`/api/auth/school/login`, credential)
            return response;
        } catch (error: any) {
            
            if (error.response.status === 422) {
                console.log(error.response.data)
                throw error?.response.data.errors;
            } else {
                throw error;
            }
        }
    }
    const teahubLogin = async (credential: CredentialType) => {
        try {
            await axiosDefault.guestAxiosInstance().get(`/sanctum/csrf-cookie`);
            const response = await axiosDefault.guestAxiosInstance().post(`/api/auth/qad/login`, credential)
            return response;
        } catch (error: any) {
          
            if (error.response.status === 422) {
                console.log(error.response.data)
                throw error?.response.data.errors;
            } else {
                throw error;
            }
        }
    }
    const logout = async (role: string) => {
        logging_out.value = true;
        try {
            await axiosDefault.authAxiosInstances().post(`/api/auth/logout`);
            setTimeout(() => {
                logging_out.value = false;
                authUser.value = {};
                localStorage.removeItem("token");
                localStorage.removeItem("role");
                if (role === 'Qad') return navigateTo({ name: 'Qad' })
                if (role === 'School') return navigateTo('/')



            }, 1000);
        } catch (error: any) {
            logging_out.value = false;
        }
    };
    const getOtpInfo = async (token: string | string[]) => {
        try {
            const response = await axiosDefault.guestAxiosInstance().get(`/api/auth/otp-data/${token}`);
            return response.data;
        } catch (error: any) {
            throw error.response;
        }
    }
    const otpVerification = async (otp: Ref, verification_id: string | RouteParamValue[], role: string = 'Qad') => {
        try {
            const response = await axiosDefault.guestAxiosInstance().post(
                `/api/auth/otp-verification`, { otp: otp.value.join(''), verification_id: verification_id, role: role });
            return response.data;
        } catch (error: any) {

            throw error.response.data.errors;
        }
    }

    const resendOtp = async (token: string | RouteParamValue[]) => {
        try {
            const response = await axiosDefault.guestAxiosInstance().put(
                `/api/auth/otp-resend-verification`, { verification_id: token });
            return response.data;
        } catch (error: any) {
            throw error.response;
        }
    }

    const userInfo = async () => {
        try {
            const response = await axiosDefault.authAxiosInstances().get(
                `/api/qad/userInfo`
            );
            authUser.value = response.data;

        } catch (error: any) {
            authUser.value = {};
            localStorage.removeItem("token");
            localStorage.removeItem('role');
            throw error;

        }
    };
    const schoolInfo = async () => {
        try {
            const response = await axiosDefault.authAxiosInstances().get(
                `/api/school/schoolInfo`
            );
            authUser.value = response.data[0];
            localStorage.setItem("role", response.data[1]);
        } catch (error: any) {
            authUser.value = {};
            localStorage.removeItem("token");
            localStorage.removeItem('role');
            throw error;

        }
    };
    return { authUser, logging_out, teahubLogin, schoolInfo, schoolLogin, getOtpInfo, otpVerification, resendOtp, userInfo, logout }
})