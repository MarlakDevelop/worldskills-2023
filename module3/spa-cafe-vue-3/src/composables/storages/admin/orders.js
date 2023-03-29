import {defineStore} from "pinia";
import {ref} from "vue";

const useAdminOrdersStore = defineStore('admin-orders', () => {
    const orders = ref([]);

    const refreshOrders = async (workShiftId) => {

    }
    return {orders, refreshOrders}
});