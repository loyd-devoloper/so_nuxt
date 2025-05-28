
import {useAxiosDefaultStore} from "~/stores/AxiosDefault";
import type {SdoCredentialsType} from "#shared/types/Qad/SdoAccountType";

export async function storeSdoAccount(schoolCredentials: SdoCredentialsType){
    try
    {
        const response =   await useAxiosDefaultStore().authAxiosInstances().post("/api/qad/store/sdo-account", schoolCredentials);
        return response.data;
    }catch(error:any)
    {
        throw error.response.data.errors;
    }

}