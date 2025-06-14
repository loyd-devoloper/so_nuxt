/* eslint-disable @typescript-eslint/no-explicit-any */
import {useAxiosDefaultStore} from "~/stores/AxiosDefault";
import { PaginateAttr } from "~/shared/enums/PaginateAttr";
import type { QadAccount } from "~/shared/types/Qad/TransactionType";


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

export async function fetchApplication(applicationId: any,search: string){
    try
    {
        const response =   await useAxiosDefaultStore()
            .authAxiosInstances()
            .get(`/api/qad/transaction/show/${applicationId}?q=${search}`);
        return response.data;
    }catch(error:any)
    {
        throw error.response.data.errors;
    }

}

export async function changeStudentStatus(data:{student_id: string,type: string}){
    try
    {
        const response =   await useAxiosDefaultStore()
            .authAxiosInstances()
            .post(`/api/qad/transaction/update/student`,data);
        return response.data;
    }catch(error:any)
    {
        throw error.response.data.errors;
    }

}

export async function getQadAccounts(){
    try
    {
        const response =   await useAxiosDefaultStore()
            .authAxiosInstances()
            .get(`/api/qad/transaction/qad/accounts`);
        return response.data;
    }catch(error:any)
    {
        throw error.response.data.errors;
    }

}

export async function assignValidator(applicationId: string,assignValidator: QadAccount){
    try
    {
        
        const response =   await useAxiosDefaultStore()
            .authAxiosInstances()
            .post(`/api/qad/transaction/assign-validator/${applicationId}`,assignValidator);
        
        return response.data.success;
    }catch(error:any)
    {
       
        throw error.response.data.errors;
    }

}
export async function updateValidatorStatus(applicationId: any,type: string){
    try
    {
        
        const response =   await useAxiosDefaultStore()
            .authAxiosInstances()
            .post(`/api/qad/transaction/update/validator-status/${applicationId}`,{type:type});
        return response.data.success;
    }catch(error:any)
    {
       
        throw error.response.data;
    }

}


export async function approveApplication(applicationId: any){
    try
    {
        
        const response =   await useAxiosDefaultStore()
            .authAxiosInstances()
            .post(`/api/qad/transaction/approved-application/${applicationId}`);
        return response.data.success;
    }catch(error:any)
    {
       
        throw error.response.data;
    }

}

export async function generateSo(applicationId: any){
    try
    {
        
        const response =   await useAxiosDefaultStore()
            .authAxiosInstances()
            .get(`/api/qad/transaction/generate-so/${applicationId}`,{
            responseType: 'blob'
        });
         const blob = new Blob([response.data], { type: 'application/pdf' });
        return URL.createObjectURL(blob);
    }catch(error:any)
    {
       
        throw error.response.data;
    }

}