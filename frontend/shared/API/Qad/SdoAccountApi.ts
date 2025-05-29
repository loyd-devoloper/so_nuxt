
import {useAxiosDefaultStore} from "~/stores/AxiosDefault";
import type {SdoCredentialsType} from "#shared/types/Qad/SdoAccountType";
import {PaginateAttr} from "#shared/enums/PaginateAttr";
import type {Ref} from "vue";

export async function storeSdoAccount(schoolCredentials: SdoCredentialsType){
    try
    {
        const response =   await useAxiosDefaultStore().authAxiosInstances().post("/api/qad/store/sdo-account", schoolCredentials);
        return response.data.success;
    }catch(error:any)
    {
        throw error.response.data.errors;
    }

}
export async function fetchSdoAccount(page: Ref,search: string,sortColumn: Ref){
    try
    {
        const response =   await useAxiosDefaultStore().authAxiosInstances().get(`/api/qad/sdo-accounts?q=${search}&limit=${PaginateAttr.LIMIT}&page=${page.value}&${sortColumn.value}`);
        return response.data;
    }catch(error:any)
    {
        throw error.response.data.errors;
    }

}

export async function editSdoAccount(sdo_account_id: string | number){
    try
    {
        const response =   await useAxiosDefaultStore().authAxiosInstances().get(`/api/qad/edit/sdo-account/${sdo_account_id}`);
        return response.data;
    }catch(error:any)
    {
        throw error.response;
    }

}

export async function updateSdoAccount(sdo_account_id: string | number,schoolCredentials: SdoCredentialsType){
    try
    {
        const response =   await useAxiosDefaultStore().authAxiosInstances().post(`/api/qad/update/sdo-account/${sdo_account_id}`,schoolCredentials);
        return response.data.success;
    }catch(error:any)
    {
        throw error.response.data.errors;
    }

}