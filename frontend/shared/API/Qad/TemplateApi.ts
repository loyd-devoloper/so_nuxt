import { useAxiosDefaultStore } from "~/stores/AxiosDefault";
import type { FirstTimeLoginType } from "#shared/types/School/firstTimeLoginType";
import type { NewApplicationType, StudentType } from "#shared/types/School/SchoolApplicationType";
import { PaginateAttr } from "~/shared/enums/PaginateAttr";

export async function storeNewTemplate(file: File | null, type: string) {
    try {
        const formData = new FormData();
        formData.append('file', file || '');
        formData.append('type', type || '');
        const response = await useAxiosDefaultStore()
            .authAxiosInstances()
            .post(`/api/qad/template/store`, formData);
        return response.data.success;
    } catch (error: any) {
        throw error.response.data.errors;
    }

}

export async function deleteTemplate(template_id: string) {
    try {

        const response = await useAxiosDefaultStore()
            .authAxiosInstances()
            .post(`/api/qad/template/destroy/${template_id}`);
        return response.data.success;
    } catch (error: any) {
        throw error.response.data;
    }

}

export async function fetchTemplate(page: Ref, search: string, sortColumn: Ref, direction: Ref) {
    try {
        const response = await useAxiosDefaultStore()
            .authAxiosInstances()
            .get(`/api/qad/template?q=${search}&limit=${PaginateAttr.LIMIT}&page=${page.value}&sort=${sortColumn.value}&d=${direction.value}`);
        return response.data;
    } catch (error: any) {
        throw error.response.data.errors;
    }

}
export async function downloadTemplate(template_id: string,file_name: string) {
    try {
        const response = await useAxiosDefaultStore()
            .authAxiosInstances().get(
                `/api/qad/template/download/${template_id}`,
                {
                    responseType: "blob",
                }
            );

     const mimeType =  response.headers['content-type'];
  
        const blob = new Blob([response.data], {
            type: mimeType,
        });
        const url = window.URL.createObjectURL(blob);
        
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', file_name);
        document.body.appendChild(link);
        link.click();
        
        // Cleanup
        link.remove();
        setTimeout(() => window.URL.revokeObjectURL(url), 100);
   
    } catch (error: any) {
        throw error.response;
    }
}