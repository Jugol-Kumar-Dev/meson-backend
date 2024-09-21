<template>

    <Layout>
        <Head title="Category List"/>
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Category List</h2>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right">
                    <button class="dt-button add-new btn btn-primary" tabindex="0" type="button" data-bs-toggle="modal"
                            data-bs-target="#createNewCategory"><span>Add New Category</span></button>
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
                        <tr v-for="category in categories.data" :key="category.id">
                            <td>
                                <div class="me-1">
                                    <img :src="category.photo" alt="{{ category.name }}" height="32" width="32">
                                </div>
                            </td>
                            <td>{{ category?.name }}</td>
                            <td>{{ category.slug }}</td>
                            <td>
                                <div class="demo-inline-spacing">
                                    <button @click="editCategory(category.edit_url)"
                                            class="btn btn-icon btn-icon rounded-circle bg-light-primary waves-effect waves-float waves-light">
                                        <Icon title="pencil"/>
                                    </button>
                                    <button type="button"
                                            @click="showItem(category.slug)"
                                            class="btn btn-icon btn-icon rounded-circle bg-light-warning waves-effect waves-float waves-light">
                                        <Icon title="eye"/>
                                    </button>
                                    <button @click="deleteItemModal(category.id)" type="button"
                                            class="btn btn-icon btn-icon rounded-circle waves-effect waves-float waves-light bg-light-danger">
                                        <Icon title="trash"/>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <Pagination :links="categories.links" :from="categories.from" :to="categories.to"
                                :total="categories.total"/>
                </div>
                <!-- Modal to add new user starts-->
                <Modal id="createNewCategory" title="Create New Category" v-vb-is:modal>
                    <form @submit.prevent="createNewCategory">
                        <div class="modal-body">
                            <Text v-model="createForm.name" :error="props.errors.name" label="Category Name" placeholder="Category Name"/>
                            <Image v-model="createForm.photo"  :error="props.errors.photo" label="Category Image"/>
                        </div>
                        <div class="modal-footer">
                            <button :disabled="createForm.processing" type="submit"
                                    class="btn btn-primary waves-effect waves-float waves-light">Submit
                            </button>
                            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                                    aria-label="Close">Cancel
                            </button>
                        </div>
                    </form>
                </Modal>
                <!-- Modal to add new user Ends-->
            </div>
            <!-- list and filter end -->
        </section>

        <!--    show modal-->
        <Modal id="showItem" title="Show Category" v-vb-is:modal>
            <div class="modal-dialog modal-lg modal-dialog-centered d-flex flex-column">
                <img :src="showData.photo" :alt="showData.name" class="avatar" width="60" height="60">
                <h3 class="mt-2">{{ showData.name }}</h3>
            </div>
            <div class="modal-dialog-centered mx-auto mb-1">
                <button class="btn bg-light-primary me-2">Edit</button>
                <button class="btn bg-light-danger">Delete</button>
            </div>
        </Modal>
        <!-- Modal to edit category-->
        <Modal id="updateCategory" title="Edit Category" v-vb-is:modal>
            <form @submit.prevent="updateNewCategory(editData.id)">
                <div class="modal-body">
                    <Text v-model="updateForm.name" label="Category Name" placeholder="Category Name"/>
                    <Image v-model="updateForm.photo" label="Category Image"/>
                </div>
                <div class="modal-footer">
                    <button :disabled="createForm.processing" type="submit"
                            class="btn btn-primary waves-effect waves-float waves-light">Submit
                    </button>
                    <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                            aria-label="Close">Cancel
                    </button>
                </div>
            </form>
        </Modal>
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
        categories: Object,
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
                router.delete(props.url + "/" + id, {
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

    let createNewCategory = () => {
        // console.log(createForm);
        createForm.post(props.url, {
            onSuccess: () => {
                document.getElementById('createNewCategory').$vb.modal.hide()
                createForm.reset()
            }
        });
    }

    let editData = ref({});

    let editCategory = (url) => {
        axios.get(url).then(res => {
            editData.value = res.data
            updateForm.name = res.data.name
            updateForm.photo = res.data.photo
            document.getElementById('updateCategory').$vb.modal.show()
            console.log(res.data)
        }).catch(err => {
            console.log(err.data)
        })
    }

    let updateNewCategory = (id) =>{
        console.log(updateForm);
        updateForm.post('/update-cat/'+id, {
            onSuccess: ()=>{
                document.getElementById('updateCategory').$vb.modal.hide()
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
