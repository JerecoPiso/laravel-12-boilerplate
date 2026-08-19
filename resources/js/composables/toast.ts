import { useToast } from 'primevue/usetoast'

export function useAppToast() {
    const toast = useToast()

    const success = (detail: string, summary: string = 'Success', life: number = 3000) => {
        toast.add({
            severity: 'success',
            summary,
            detail,
            life
        })
    }

    const error = (detail: string, summary: string = 'Error', life: number = 3000) => {
        toast.add({
            severity: 'error',
            summary,
            detail,
            life
        })
    }

    const info = (detail: string, summary: string = 'Info', life: number = 3000) => {
        toast.add({
            severity: 'info',
            summary,
            detail,
            life
        })
    }

    const warn = (detail: string, summary: string = 'Warning', life: number = 3000) => {
        toast.add({
            severity: 'warn',
            summary,
            detail,
            life
        })
    }

    return {
        success,
        error,
        info,
        warn
    }
}
