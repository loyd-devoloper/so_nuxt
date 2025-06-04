import {useAxiosDefaultStore} from "~/stores/AxiosDefault";

import {PaginateAttr} from "#shared/enums/PaginateAttr";
import type {Ref} from "vue";
import type {SchoolCredentialsType} from "#shared/types/Qad/SchoolAccount";
import type {FirstTimeLoginType} from "#shared/types/School/firstTimeLoginType";

export async function storeSchoolAccount(schoolCredentials: SchoolCredentialsType){
    try
    {
        const response =   await useAxiosDefaultStore().authAxiosInstances().post("/api/qad/school-account/store", schoolCredentials);
        return response.data.success;
    }catch(error:any)
    {
        throw error.response.data.errors;
    }

}
export async function storeSchoolBulkAccount(file: Ref){
    try
    {
        const formData = new FormData();
        formData.append('excel_file',file.value);
        const response =   await useAxiosDefaultStore().authAxiosInstances().post("/api/qad/school-account/store-bulk", formData);
        return response.data;
    }catch(error:any)
    {
        throw error.response.data.errors;
    }

}
export async function fetchSchoolAccount(page: Ref,search: string,sortColumn: Ref,direction: Ref){
    try
    {
        const response =   await useAxiosDefaultStore()
            .authAxiosInstances()
            .get(`/api/qad/school-account?q=${search}&limit=${PaginateAttr.LIMIT}&page=${page.value}&sort=${sortColumn.value}&d=${direction.value}`);
        return response.data;
    }catch(error:any)
    {
        throw error.response.data.errors;
    }

}

export async function editSchoolAccount(school_id: string | number){
    try
    {
        const response =   await useAxiosDefaultStore().authAxiosInstances().get(`/api/qad/school-account/edit/${school_id}`);
        return response.data;
    }catch(error:any)
    {
        throw error.response;
    }

}
export async function sdoList(){
    try
    {
        const response =   await useAxiosDefaultStore().authAxiosInstances().get(`/api/qad/school-account/sdo-list`);
        return response.data;
    }catch(error:any)
    {
        throw error.response;
    }

}
export async function updateSchoolAccount(sdo_account_id: string | number,schoolCredentials: FirstTimeLoginType){
    try
    {
        const response =   await useAxiosDefaultStore().authAxiosInstances().post(`/api/qad/school-account/update/${sdo_account_id}`,schoolCredentials);
        return response.data.success;
    }catch(error:any)
    {
        throw error.response.data;
    }

}

export async function viewAttachment(attachment_id?: string | number){
    try
    {
        const response =   await useAxiosDefaultStore().authAxiosInstances().get(`/api/qad/school-account/attachment/${attachment_id}`,{
            responseType: 'blob'
        });
        const blob = new Blob([response.data], { type: 'application/pdf' });
        return URL.createObjectURL(blob);

    }catch(error:any)
    {
        throw error.response.data.errors;
    }

}