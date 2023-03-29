import {defineStore} from "pinia";
import {ref} from "vue";

const useNotificationsStore = defineStore('notifications', () => {
    const notifications = ref([])
    const notificationsCounter = ref(0)

    const createNotification = (name, text, type) => {
        notifications.value.counter += 1
        const id = notificationsCounter.value
        notifications.value.push({
            id, name, text, type,
            close: removeNotification(id)
        })
    }
    const removeNotification = (id) => {
        return () => {
            notifications.value = [...notifications.value.filter(elem => elem.id !== id)]
        }
    }
    return {notifications: notifications.value, createNotification}
})