export type NewApplicationType =  {
    [key: string]: any;
    attestation_file?: File | null;
    form_9_file?: File | null;
    students_file?: File | null;
    graduation_date?: string;
    curriculum_id?: string;
    applied_track?:string;
    applied_strand?:string;
    applied_specialization?: string[] | number[]

};

export type StudentType = {
    [key: string] : any;
    last_name?: string;
    first_name?:string;
    middle_name?: string;
        suffix?: string;
    lrn?:string;
}