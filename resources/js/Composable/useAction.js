import Swal from "sweetalert2";
import {ref} from "vue";
import {router} from "@inertiajs/vue3";


export function useAction(){

    const swalSuccess = (msg = null) =>{
        Swal.fire(
            'Saved!',
            msg  ?? 'Your file has been Saved.',
            'success'
        )
    }
    const swalError = (msg = null) =>{
        Swal.fire(
            'Saved!',
            msg  ?? 'Action Failed. Try Again',
            'success'
        )
    }
    const deleteItem = (main_url, id) => {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                router.delete(main_url + "/" + id, {
                    preserveState: true, replace: true, onSuccess: page => {
                        Swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        )
                    },
                    onError: errors => {
                        Swal.fire(
                            'Oops...',
                            'Something went wrong!',
                            'error'
                        )
                    }
                })
            }
        })
    };

    const isShow = ref(false);
    const changeIsShow = (value) => isShow.value = value;
    const chatStyle = ''//import(process.env?.CHAT_STYLE)


    return {swalSuccess, swalError, deleteItem, isShow, changeIsShow, chatStyle}
}
