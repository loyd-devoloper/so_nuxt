
import {useAxiosDefaultStore} from "~/stores/AxiosDefault";
import type {SdoCredentialsType} from "#shared/types/Qad/SdoAccountType";
import {PaginateAttr} from "#shared/enums/PaginateAttr";
import type {Ref} from "vue";

export async function storeSdoAccount(sdoAccountCredential: SdoCredentialsType){
    try
    {
        const response =   await useAxiosDefaultStore().authAxiosInstances().post("/api/qad/sdo-account/store", sdoAccountCredential);
        return response.data.success;
    }catch(error:any)
    {
        throw error.response.data.errors;
    }

}
export async function fetchSdoAccount(page: Ref,search: string,sortColumn: Ref,direction: Ref){
    try
    {
        const response =   await useAxiosDefaultStore()
                                                        .authAxiosInstances()
                                                        .get(`/api/qad/sdo-account?q=${search}&limit=${PaginateAttr.LIMIT}&page=${page.value}&sort=${sortColumn.value}&d=${direction.value}`);
        return response.data;
    }catch(error:any)
    {
        throw error.response.data.errors;
    }

}

export async function editSdoAccount(sdo_account_id: string | number){
    try
    {
        const response =   await useAxiosDefaultStore().authAxiosInstances().get(`/api/qad/sdo-account/edit/${sdo_account_id}`);
        return response.data;
    }catch(error:any)
    {
        throw error.response;
    }

}

export async function updateSdoAccount(sdo_account_id: string | number,sdoAccountCredential: SdoCredentialsType){
    try
    {
        const response =   await useAxiosDefaultStore().authAxiosInstances().post(`/api/qad/sdo-account/update/${sdo_account_id}`,sdoAccountCredential);
        return response.data.success;
    }catch(error:any)
    {
        throw error.response.data.errors;
    }

}