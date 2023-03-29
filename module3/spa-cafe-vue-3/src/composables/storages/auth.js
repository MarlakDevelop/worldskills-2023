import {defineStore} from "pinia";
import {useLocalStorage} from "@/composables/utils/localStorage";
import {useApiFetch} from "@/composables/apiFetch";

export const useAuthStore = defineStore('auth', () => {
    const [token, setToken] = useLocalStorage('token', undefined)
    const apiFetch = useApiFetch()
    const login = async ({login, password}) => {
        token
    }

    const logout = async () => {
        await apiFetch('', {
            method: 'GET'
        });
        setToken(undefined)
    }

    return {token, login, logout}
})