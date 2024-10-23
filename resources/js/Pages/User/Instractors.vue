<template>
    <Layout>
        <Head title="Admin List" />
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Instructors List</h2>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right">
                    <button class="dt-button add-new btn btn-primary" tabindex="0" type="button" data-bs-toggle="modal"
                        data-bs-target="#createNewAdmin"><span>Add New Instructor</span></button>
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
                                <label>Search:<input v-model="search" type="search" class="form-control" placeholder=""
                                        aria-controls="DataTables_Table_0"></label>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="user-list-table table">
                    <thead class="table-light">
                        <tr class="">
                            <th class="sorting">Name</th>
                            <th class="sorting">Phone</th>
                            <th class="sorting">Active on</th>
                            <th class="sorting">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="admin in instructors.data" :key="admin.id">
                            <td>
                                <div class="d-flex justify-content-left align-items-center">
                                    <div class="avatar-wrapper">
                                        <div class="avatar  me-1">
                                            <img :src="admin.photo_url"
                                                :alt="admin.name" height="32" width="32">
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <div class="admin_name text-truncate text-body">
                                            <span class="fw-bolder">{{ admin.name }}</span>
                                        </div>
                                        <small class="emp_post text-muted">{{ admin.email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ admin.phone }}</td>
                            <td>{{ admin.created_at }}</td>
                            <td>
                                <div class="demo-inline-spacing">
<!--                                    <button type="button" class="btn btn-icon btn-icon rounded-circle btn-warning waves-effect waves-float waves-light">-->
<!--                                        <Icon title="eye" />-->
<!--                                    </button>-->
                                    <button @click="deleteItemModal(admin.id)" type="button" class="btn btn-icon btn-icon rounded-circle btn-warning waves-effect waves-float waves-light btn-danger">
                                        <Icon title="trash" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <Pagination :links="instructors.links" :from="instructors.from" :to="instructors.to" :total="instructors.total" />
            </div>
            <!-- Modal to add new Admin starts-->
            <Modal id="createNewAdmin" title="Create New User here" v-vb-is:modal>
                <form @submit.prevent="createNewAdmin">
                    <div class="modal-body">
                        <Text v-model="createForm.name" type="text" label="Name" placeholder="Name" :error="props.errors.name" />
                        <Text v-model="createForm.email" type="email" label="Email" placeholder="Email Address" :error="props.errors.email" />
                        <Text v-model="createForm.phone" type="text" label="Phone" placeholder="Phone Number" :error="props.errors.phone" />
                        <Password v-model="createForm.password" label="Password" :error="props.errors.password" />
                        <Image v-model="createForm.photo" label="Photo" :error="props.errors.photo" />
                    </div>
                    <div class="modal-footer">
                        <button :disabled="createForm.processing" type="submit" class="btn btn-primary waves-effect waves-float waves-light">Submit</button>
                        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                    </div>
                </form>
            </Modal>
            <!-- Modal to add new Admin Ends-->
        </div>
        <!-- list and filter end -->
    </section>
    </Layout>
</template>

<script setup>
import Pagination from '@/Components/Pagination.vue'
import Modal from '@/Components/Modal.vue'
import Icon from '@/Components/Icon.vue'
import Password from '@/Components/form/Password.vue'
import Text from '@/Components/form/Text.vue'
import Image from '@/Components/form/Image.vue'
import { ref, watch } from 'vue';
import debounce from "lodash/debounce"
import Swal from 'sweetalert2'
import {router, useForm} from "@inertiajs/vue3";
import Layout from "@/Shared/Layout.vue";
import Select from "@/Components/form/Select.vue";
let props = defineProps({
    instructors: Object,
    filters: Object,
    errors:Object,
    url: String,
});

let createForm = useForm({
    name: '',
    email: '',
    phone: '',
    designation: '',
    role: 'instructor',
    photo: '',
    password: '',
});

let createNewAdmin = () => {
    router.post("/panel/admins", createForm,{
        onSuccess: () => document.getElementById('createNewAdmin').$vb.modal.hide()
    });
}

let search = ref(props.filters.search);
let perPage = ref(props.filters.perPage);

watch([search, perPage], debounce(function ([val, val2]) {
    router.get('', { search: val, perPage: val2 }, { preserveState: true, replace: true });
}, 300));

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
            router.delete(props.url + '/' + id, { preserveState: true, replace: true, onSuccess: page => {
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
            }})
        }
    })
};

</script>
