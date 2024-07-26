<template>
    <Head title="Course List"/>
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-start mb-0">Comment List</h2>
                </div>
            </div>
        </div>
        <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
            <div class="mb-1 breadcrumb-right">
                <Link :href="props.url+'/create'" class="dt-button add-new btn btn-primary">
                    <span>Add New Blog</span>
                </Link>
            </div>
        </div>
    </div>
    <section class="app-user-list">
        <!-- list and filter start -->
        <div class="card">
            <div class="card pt-0">
                <div class="d-flex justify-content-between align-items-center header-actions mx-0 row mt-75">
                    <table class="user-list-table table">
                        <thead class="table-light">
                        <tr class="">
                            <th class="sorting">Image</th>
                            <th class="sorting">Name</th>
                            <th class="sorting">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="comment in blog.comments" :key="comment.id">
                            <td>
                                <div class="d-flex justify-content-left align-items-center">
                                    <div class="avatar-wrapper">
                                        <div class="avatar  me-1">
                                            <img :src="comment.user.photo"
                                                 :alt="comment.user.name" height="32" width="32">
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <div class="instructor_name text-truncate text-body">
                                            <span class="fw-bolder">{{ comment.user.name }}</span>
                                        </div>
                                        <small class="emp_post text-muted">{{ comment.user.email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ comment.message }}</td>
                            <td class="d-flex">
                                <!--  <div class="demo-inline-spacing">
                                      <button @click="deleteItemModal(comment.id)"
                                              class="btn btn-icon btn-icon rounded-circle bg-light-primary waves-effect waves-float waves-light">
                                          <Icon title="check"/>
                                      </button>
                                  </div>


                                <div class="demo-inline-spacing">
                                    <button @click="editCategory(comment.id)"
                                            class="btn btn-icon btn-icon rounded-circle bg-light-warning waves-effect waves-float waves-light">
                                        <Icon title="disc"/>
                                    </button>
                                </div>
                                -->
                                <div class="demo-inline-spacing">
                                    <button @click="deleteItemModal(comment.id)"
                                            class="btn btn-icon btn-icon rounded-circle bg-light-danger waves-effect waves-float waves-light">
                                        <Icon title="trash"/>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import Icon from '@/Components/Icon.vue';
import Swal from "sweetalert2";
import {router} from "@inertiajs/vue3";
    let props = defineProps({
        blog: Object,
    });
    let color = [
        'text-success',
        'text-danger',
        'text-primary',
        'text-secondary',
        'text-warning',
        'text-info'
    ]

    let deleteItemModal = (id) => {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#7121f5',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                router.delete(`/panel/blogs/comments/delete/${id}`, {
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


</script>

<style scoped>
    .action_button{
        border-radius: 100%;
        height: 34px;
        background: white;
        position: absolute;
        border:1px solid #f1f1f1;
        color: #6e6e6e;
    }
    .position_size{
        right: 22px;
        top: -14px;
    }
</style>
