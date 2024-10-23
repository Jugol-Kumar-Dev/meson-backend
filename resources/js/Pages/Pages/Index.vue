<template>

    <Layout>
        <Head title="Page List"/>
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Page List</h2>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right">
                    <Link href="/panel/pages/create" class="dt-button add-new btn btn-primary"><span>Add New Page</span></Link>
                </div>
            </div>
        </div>
        <section class="app-user-list">
            <!-- list and filter start -->
            <div class="card">
                <div class="card-datatable table-responsive pt-0">
                    <div class="d-flex justify-content-between align-items-center header-actions mx-0 row mt-75">
                        <div class="col-sm-12 col-lg-4 d-flex justify-content-center justify-content-lg-start">
                            <div class="select-search-area">
                                <label>Show <select class="form-select" v-model="perPage">
                                    <option :value="undefined">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select> entries</label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-lg-8 ps-xl-75 ps-0">
                            <div
                                class="d-flex align-items-center justify-content-center justify-content-lg-end flex-lg-nowrap flex-wrap">
                                <div class="select-search-area">
                                    <label>Search:<input v-model="search" type="search" class="form-control"
                                                         placeholder="Type here for search"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table class="user-list-table table">
                        <thead class="table-light">
                        <tr class="">
                            <th class="sorting">Image</th>
                            <th class="sorting">Name</th>
                            <th class="sorting">Slug</th>
                            <th class="sorting">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="page in pages.data" :key="page.id">
                            <td>{{ page?.id }}</td>
                            <td>{{ page?.title }}</td>
                            <td>{{ page?.slug }}</td>
                            <td>

                                <div class="btn-group dropup dropdown-icon-wrapper">
                                    <button type="button"
                                            class="btn dropdown-toggle dropdown-toggle-split waves-effect waves-float waves-light"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                                    </button>

                                    <div class="dropdown-menu">
                                        <Link :href="`/panel/pages/${page.slug}?page=edit`" class="dropdown-item">
                                            <Icon title="pencil"/>
                                           <span class="ms-1">Edit</span>
                                        </Link>
                                        <span class="dropdown-item"
                                              @click="deleteItemModal(page.id)">
                                            <Icon title="trash"/>
                                           <span class="ms-1">Delete</span>
                                        </span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <Pagination :links="pages.links" :from="pages.from" :to="pages.to"
                                :total="pages.total"/>
                </div>
            </div>
            <!-- list and filter end -->
        </section>
    </Layout>
</template>

<script setup>
import Pagination from '@/Components/Pagination.vue';
import Modal from '@/Components/Modal.vue';
import Icon from '@/Components/Icon.vue';
import Text from '@/Components/form/Text.vue';
import Image from '@/Components/form/Image.vue';
import axios from 'axios';
import {ref, watch, computed} from 'vue';
import debounce from "lodash/debounce";
import Swal from 'sweetalert2'
import {router, useForm} from "@inertiajs/vue3";
import Layout from "@/Shared/Layout.vue";

let props = defineProps({
    pages: Object,
    filters: Object,
    url: String,
    errors:Object
});


let deleteItemModal = (id) => {
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
            router.delete(props.url +"/"+ id, {
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
let createForm = useForm({
    name: '',
    photo: null,
});

let updateForm = useForm({
    name: '',
    photo: null,
})

let createNewPage = () => {
    // console.log(createForm);
    createForm.post(props.url, {
        onSuccess: () => {
            document.getElementById('createNewPage').$vb.modal.hide()
            createForm.reset()
        }
    });
}

let editData = ref({});

let editPage = (url) => {
    axios.get(url).then(res => {
        editData.value = res.data
        updateForm.name = res.data.name
        updateForm.photo = res.data.photo
        document.getElementById('updatePage').$vb.modal.show()
        console.log(res.data)
    }).catch(err => {
        console.log(err.data)
    })
}

let updateNewPage = (id) =>{
    console.log(updateForm);
    updateForm.post('/update-cat/'+id, {
        onSuccess: ()=>{
            document.getElementById('updatePage').$vb.modal.hide()
            updateForm.reset()
        }
    })
}


let showData = ref([])
let showItem = (slug) => {
    axios.get('categories/' + slug).then(res => {
        showData.value = res.data;
        document.getElementById('showItem').$vb.modal.show()
    }).catch(err => {
        console.log(err)
    })
}


let search = ref(props.filters.search);
let perPage = ref(props.filters.perPage);

watch([search, perPage], debounce(function ([val, val2]) {
    Inertia.get(props.url, {search: val, perPage: val2}, {preserveState: true, replace: true});
}, 300));

</script>
