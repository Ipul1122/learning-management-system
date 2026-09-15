import Alpine from 'alpinejs';
import Swal from 'sweetalert2';

// Register SweetAlert2 globally
window.Swal = Swal;

// Custom Toast Preset with LMS Orange & Red Palette
window.Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3500,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
    },
    customClass: {
        popup: 'font-quicksand rounded-2xl shadow-xl border border-[#EBE5DF]',
        title: 'font-montserrat font-bold text-sm text-[#1E1B18]',
    }
});

// Helper for Confirm Dialogs (e.g. Reject Kelulusan, Hapus Data, Pendaftaran Kelas)
window.confirmAction = ({ title, text, icon = 'warning', confirmText = 'Ya, Lanjutkan', cancelText = 'Batal' }) => {
    return Swal.fire({
        title: title,
        text: text,
        icon: icon,
        showCancelButton: true,
        confirmButtonColor: '#FF6B00',
        cancelButtonColor: '#DC2626',
        confirmButtonText: confirmText,
        cancelButtonText: cancelText,
        reverseButtons: true,
        customClass: {
            popup: 'font-quicksand rounded-3xl p-6 border border-[#EBE5DF] shadow-2xl',
            title: 'font-montserrat font-bold text-xl text-[#1E1B18]',
            htmlContainer: 'text-sm text-[#6E675F]',
            confirmButton: 'px-5 py-2.5 rounded-xl font-montserrat font-bold text-sm text-white shadow-sm',
            cancelButton: 'px-5 py-2.5 rounded-xl font-montserrat font-bold text-sm text-white shadow-sm mr-2',
        },
        buttonsStyling: false
    });
};

window.Alpine = Alpine;
Alpine.start();

