<template>

    <Layout>

    <Head title="faq List"/>
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-start mb-0">faq List</h2>
                </div>
            </div>
        </div>
        <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
            <div class="mb-1 breadcrumb-right">
                <button class="dt-button add-new btn btn-primary" tabindex="0" type="button" data-bs-toggle="modal"
                        data-bs-target="#createNewfaq"><span>Add New faq</span></button>
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
                        <th class="sorting">Title</th>
                        <th class="sorting">Answer</th>
                        <th class="sorting">Status</th>
                        <th class="sorting">Created</th>
                        <th class="sorting">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="faq in faqs.data" :key="faq.id">
                        <td> {{ faq.id }} </td>
                        <td>{{ faq.title }}</td>
                        <td>{{ faq.description }}</td>
                        <td><span class="badge bg-light-success">{{ faq.category }}</span></td>
                        <td>{{ faq.created_at }}</td>
                        <td>
                            <div class="demo-inline-spacing">
                                <button @click="deleteItemModal(faq.id)" type="button"
                                        class="btn btn-icon btn-icon rounded-circle waves-effect waves-float waves-light bg-light-danger">
                                    <Icon title="trash"/>
                                </button>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <Pagination :links="faqs.links" :from="faqs.from" :to="faqs.to"
                            :total="faqs.total"/>
            </div>

        </div>
        <!-- list and filter end -->
    </section>

    <!-- Modal to add new user starts-->
    <Modal id="createNewfaq" title="Create New faq" v-vb-is:modal>
        <form @submit.prevent="createNewfaq">
            <div class="modal-body">

                <div class="mb-1">
                    <label class="form-label">Select a Category</label>
                    <v-select v-model="createForm.category" label="name" :options="status" :reduce="status => status.name"
                              placeholder="Select Questions Status"></v-select>
                    <span class="error text-danger" v-if="props.errors.category">{{ props.errors.category }}</span>
                </div>
                <Text v-model="createForm.title" :error="props.errors.title" label="FAQ's" placeholder="FAQ Title"/>
                <Textarea v-model="createForm.description" :error="props.errors.description" class="form-control" label="Answer" placeholder="Answer the questions" />

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


    <!--    show modal-->

    <Modal id="showItem" title="Show faq" v-vb-is:modal>
        <div class="modal-dialog modal-lg modal-dialog-centered d-flex flex-column">
            <img :src="showData.photo" :alt="showData.name" class="avatar" width="60" height="60">
            <h3 class="mt-2">{{ showData.name }}</h3>
        </div>
        <div class="modal-dialog-centered mx-auto mb-1">
            <button class="btn bg-light-primary me-2">Edit</button>
            <button class="btn bg-light-danger">Delete</button>
        </div>
    </Modal>


    <!-- Modal to edit faq-->
    <Modal id="updatefaq" title="Create New faq" v-vb-is:modal>
        <form @submit.prevent="updateNewfaq(editData.id)">
            <div class="modal-body">
                <Text v-model="updateForm.name" label="faq Name" placeholder="faq Name"/>
                <Image v-model="updateForm.photo" label="faq Image"/>
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
    import Textarea from '@/Components/form/Textarea.vue';
    import axios from 'axios';
    import {ref, watch, computed} from 'vue';
    import debounce from "lodash/debounce";
    import Swal from 'sweetalert2'
    import Layout from "@/Shared/Layout.vue";
    import {router, useForm} from "@inertiajs/vue3";

    const props = defineProps({
        faqs: Object,
        filters: Object,
        url: String,
        errors:Object
    });

    const status =  [{"name":'General'}, {"name":'Most Asked'}, {"name":'Support'}, {"name":'Course Questions'}];
    const createForm = useForm({
        title: null,
        category:null,
        description:null,
    });

    const updateForm = useForm({
        name: '',
        photo: null,
    })
    const deleteItemModal = (id) => {
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
                router.delete(props.url +'/' + id, {
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


    const createNewfaq = () => {
        createForm.post(props.url, {
            onSuccess: () => {
                document.getElementById('createNewfaq').$vb.modal.hide()
                createForm.reset()
            }
        });
    }

    const editData = ref({});

    const editfaq = (url) => {
        axios.get(url).then(res => {
            editData.value = res.data
            updateForm.name = res.data.name
            updateForm.photo = res.data.photo
            document.getElementById('updatefaq').$vb.modal.show()
            console.log(res.data)
        }).catch(err => {
            console.log(err.data)
        })
    }

    const updateNewfaq = (id) =>{
        console.log(updateForm);
        updateForm.post('/update-cat/'+id, {
            onSuccess: ()=>{
                document.getElementById('updatefaq').$vb.modal.hide()
                updateForm.reset()
            }
        })
    }


    const showData = ref([])
    const showItem = (slug) => {
        axios.get('faqs/' + slug).then(res => {
            showData.value = res.data;
            document.getElementById('showItem').$vb.modal.show()
        }).catch(err => {
            console.log(err)
        })
    }


    const search = ref(props.filters.search);
    const perPage = ref(props.filters.perPage);

    watch([search, perPage], debounce(function ([val, val2]) {
        Inertia.get(props.url, {search: val, perPage: val2}, {preserveState: true, replace: true});
    }, 300));

</script>
