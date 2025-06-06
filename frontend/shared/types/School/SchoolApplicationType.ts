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