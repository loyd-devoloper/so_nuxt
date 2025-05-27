import {useAxiosDefaultStore} from "~/stores/AxiosDefault";
import type {CredentialType} from "~/shared/types/LoginType";
import type {RouteParamValue} from "#vue-router";
import {AccountRoleEnum} from "#shared/enums/AccountRoleEnum";


export const useAuthStore = defineStore('authStore', () => {
    const authUser = ref({});
    const logging_out = ref<boolean>(false)
    const axiosDefault = useAxiosDefaultStore();


    const teahubLogin = async (credential: CredentialType) => {
        try {
            await axiosDefault.guestAxiosInstance().get(`/sanctum/csrf-cookie`);
            const response = await axiosDefault.guestAxiosInstance().post(`/api/auth/qad/login`, credential)
            return response.data?.token;
        } catch (error: any) {
            if (error.response.status === 422) {
                console.log(error.response.data)
                throw error?.response.data.errors;
            } else {
                throw error;
            }
        }
    }
    const logout = async (role:string) => {
        logging_out.value = true;
        try {
            await axiosDefault.authAxiosInstances().post(`/api/logout`);
            setTimeout(() => {
                logging_out.value = false;
                authUser.value = {};
                localStorage.removeItem("token");
                localStorage.removeItem("role");
                if(role === AccountRoleEnum.QAD || role === AccountRoleEnum.ADMIN){
                   return  navigateTo({name: 'Qad'})
                }
            }, 1000);
        } catch (error:any) {
            logging_out.value = false;
        }
    };
    const getOtpInfo = async (token: string | string[]) => {
        try {
            const response = await axiosDefault.guestAxiosInstance().get(`/api/otp-data/${token}`);
            return response.data;
        } catch (error: any) {
            throw error.response;
        }
    }
    const otpVerification = async (otp: Ref, token: string | RouteParamValue[]) => {
        try {
            const response = await axiosDefault.guestAxiosInstance().post(
                    `/api/otp-verification`, {otp: otp.value.join(''), token: token});
            return response.data;
        } catch (error: any) {

            throw error.response.data.errors;
        }
    }

    const resendOtp = async (token: string | RouteParamValue[]) =>{
        try{
            const response = await axiosDefault.guestAxiosInstance().post(
                    `/api/otp-resend-verification`,{token: token});
            return response.data;
        }catch(error: any)
        {
            throw error.response;
        }
    }

    const userInfo = async (role: string) => {
        try {
            const response = await axiosDefault.authAxiosInstances().post(
                    `/api/${role}/userInfo`
                );
            authUser.value = response.data;

        } catch (error: any) {
            if (error.response.status) {
                // AuthUser.value = false;
                // localStorage.removeItem("__AuthUser");
                // localStorage.removeItem("token");
                // localStorage.removeItem("code");
                // User.value = [];
                // await router.push({ name: "login" });
            }
        }
    };
    return {authUser,logging_out,teahubLogin, getOtpInfo,otpVerification,resendOtp,userInfo,logout}
})