// composables/useConfirmToast.ts
import { useConfirm } from "primevue/useconfirm";

type ShowConfirmOptions = {
    message?: string;
    header?: string;
    icon?: string;
    acceptProps?: Record<string, any>;
    rejectProps?: Record<string, any>;
    onAccept?: () => void;
    onReject?: () => void;
};

export function useConfirmToast() {
    const confirm = useConfirm();
    /**
     * Show a confirmation dialog with accept/reject callbacks
     */
    const showConfirm = ({
        message = "Are you sure?",
        header = "Confirmation",
        icon = "pi pi-exclamation-triangle",
        acceptProps = { label: "Yes" },
        rejectProps = { label: "No", severity: "secondary", outlined: true },
        onAccept = () => { },
        onReject = () => { },
    }: ShowConfirmOptions = {}) => {
        confirm.require({
            message,
            header,
            icon,
            acceptProps,
            rejectProps,
            accept: () => {
                onAccept();
            },
            reject: () => {
                onReject();
            },
        });
    };

    return { showConfirm };
}
