/* eslint-disable @typescript-eslint/no-explicit-any */
import {useAxiosDefaultStore} from "~/stores/AxiosDefault";
import { PaginateAttr } from "~/shared/enums/PaginateAttr";


export async function fetchApplications(page: Ref,search: string,sortColumn: Ref,direction: Ref){
    try
    {
        const response =   await useAxiosDefaultStore()
            .authAxiosInstances()
            .get(`/api/qad/transaction?q=${search}&limit=${PaginateAttr.LIMIT}&page=${page.value}&sort=${sortColumn.value}&d=${direction.value}`);
        return response.data;
    }catch(error:any)
    {
        throw error.response.data.errors;
    }

}

export async function fetchStudentNoPaginate(applicationId: any){
    try
    {
        const response =   await useAxiosDefaultStore()
            .authAxiosInstances()
            .get(`/api/qad/transaction/students/${applicationId}`);
        return response.data;
    }catch(error:any)
    {
        throw error.response.data.errors;
    }

}

export async function fetchApplication(applicationId: any){
    try
    {
        const response =   await useAxiosDefaultStore()
            .authAxiosInstances()
            .get(`/api/qad/transaction/show/${applicationId}`);
        return response.data;
    }catch(error:any)
    {
        throw error.response.data.errors;
    }

}