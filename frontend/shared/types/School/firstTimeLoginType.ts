export type FirstTimeLoginType =  {
    school_number?: string;
    sdo_id?: string;
    school_name?: string;
    school_address?: string;
    school_head_name?: string;
    owner_name?: string | null;
    school_contact_number?: string;
    school_email_address?: string;
    date_established?: string | Date; // Can be string (ISO format) or Date object
    admin_first_name?: string;
    admin_last_name?: string;
    admin_email_address?: string;
    admin_contact_number?: string;
    admin_suffix?: string; // Optional (e.g., "Jr.", "Sr.")
    admin_middle_name?: string; // Optional
    password?: string;
    status?: 'active' | 'inactive' | 'pending'; // Enumerated status
    is_first_time_login?: boolean;
    sec_permit?: File | null;
    shs_provisional_permit?: File | null;
    mayors_permit?: File | null;
    program_offered?: any;
    sec_expiration_date?: Date | null;
    shs_provisional_expiration_date?: Date | null;
    mayors_permit_expiration_date?: Date | null;


};
