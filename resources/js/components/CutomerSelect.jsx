import React, { useState, useEffect } from "react";
import CreatableSelect from "react-select/creatable";
import axios from "axios";

const CustomerSelect = ({ setCustomerId }) => {
    const [customers, setCustomers] = useState([]);
    // 1. تعريب القيمة الافتراضية
    const [selectedCustomer, setSelectedCustomer] = useState({value:1,label:"عميل عابر"}); 

    // Fetch existing customers from the backend
    useEffect(() => {
        axios.get("/admin/get/customers").then((response) => {
            const customerOptions = response?.data?.map((customer) => ({
                value: customer.id,
                label: customer.name,
            }));
            setCustomers(customerOptions);
        });
    }, []);
    
    useEffect(() => {
        setCustomerId(selectedCustomer?.value);
    }, [selectedCustomer]);

    const handleCreateCustomer = (inputValue) => {
        axios
            .post("/admin/create/customers", { name: inputValue })
            .then((response) => {
                const newCustomer = response.data;
                const newOption = {
                    value: newCustomer.id,
                    label: newCustomer.name,
                };
                setCustomers((prev) => [newOption,...prev]);
                setSelectedCustomer(newOption);
            })
            .catch((error) => {
                console.error("Error creating customer:", error);
            });
    };

    const handleChange = (newValue) => {
        setSelectedCustomer(newValue);
    };

    return (
        <CreatableSelect
            isClearable
            options={customers}
            onChange={handleChange}
            onCreateOption={handleCreateCustomer} // Handle creating a new customer
            value={selectedCustomer}
            // 2. تعريب النص النائب (Placeholder)
            placeholder="اختر أو أنشئ عميلاً" 
            
            // 3. تعريب رسائل CreatableSelect المدمجة
            formatCreateLabel={(inputValue) => `إنشاء عميل "${inputValue}"`}
            noOptionsMessage={() => "لا توجد خيارات"}
            
            // لتصحيح اتجاه الكتابة والقراءة داخل المكون
            styles={{
                 container: (provided) => ({
                    ...provided,
                    direction: 'rtl',
                    textAlign: 'right',
                }),
                input: (provided) => ({
                    ...provided,
                    direction: 'rtl',
                    textAlign: 'right',
                }),
                placeholder: (provided) => ({
                    ...provided,
                    direction: 'rtl',
                    textAlign: 'right',
                }),
                singleValue: (provided) => ({
                    ...provided,
                    direction: 'rtl',
                    textAlign: 'right',
                }),
            }}
        />
    );
};

export default CustomerSelect;
