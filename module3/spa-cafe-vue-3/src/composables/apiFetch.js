import {$fetch} from "ohmyfetch";
import {useAuthStore} from "@/composables/storages/auth";

export function useApiFetch(onAuthErrorCb, onForbiddenErrorCb, onValidationErrorCb, onOtherErrorCb) {
    return $fetch.create({
        parseResponse: JSON.parse,
        baseURL: 'http://127.0.0.1:8000/api-cafe',
        onRequest({options}) {
            const authStore = useAuthStore()
            const token = authStore.token
            if (token !== undefined) {
                options.headers = {
                    ...options.headers,
                    Authorization: `Bearer ${token}`
                }
            }
        },
        onResponseError({response}) {
            if (response.status === 403) {
                if (response.error.message === 'Login failed') onAuthErrorCb()
                if (response.error.message === 'Forbidden for you') onForbiddenErrorCb()
            }
            if (response.status === 422 && response.error.message === 'Validation error') onValidationErrorCb(response.error.errors)
            onOtherErrorCb()
        }
    })
}