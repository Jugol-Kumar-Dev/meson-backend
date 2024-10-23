<template>

    <Layout>
        <Head title="Category List"/>
        <div class="container-lg my-5">
            <!-- Cover Photo and Profile Section -->
            <div class="card shadow-sm">
                <!-- Cover Photo -->
                <div class="position-relative">
                    <img src="https://sb.ecobnb.net/app/uploads/sites/3/2020/01/nature-1170x490.jpg"
                         style="height:180px;object-fit: cover;" alt="Cover Photo"
                         class="card-img-top rounded-top">
                    <!-- Profile Picture -->
                    <div class="position-absolute -bottom-5 start-50 translate-middle">
                        <div class="avatar">
                            <img class="round" :src="$page.props.auth.user.photo" alt="avatar" height="100" width="100">
                        </div>

                        <!--                        <img :src="authUser?.photo_url" @error="handleImageError($event, authUser?.name)" alt="Profile Picture" class="rounded-circle border border-white" style="width: 140px; height: 140px;">-->
                    </div>
                </div>

                <!-- Profile Info -->
                <div class="text-center mt-5 pb-4 px-4">
                    <h2 class="fw-bold text-dark">
                        {{ $page?.props?.auth?.user?.username }}
                    </h2>
                    <p class="text-muted">{{ $page?.props?.auth?.user?.role }}</p>
                </div>


                <div class="d-flex align-items-center gap-1 mx-auto mb-3">
                    <Link href="/panel/groups" class="btn btn-sm btn-primary">All Posts</Link>
                    <Link href="/panel/pending-posts" class="btn btn-sm btn-primary">Pending Posts</Link>
                    <Link href="/panel/my-posts" class="btn btn-sm btn-primary">My Posts</Link>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mx-auto">
                <!-- New Post Box -->
                <div class="card shadow-sm">
                    <div class="d-flex gap-2 mb-3 p-2">
                        <img src="https://via.placeholder.com/40" alt="Profile" class="rounded-circle"
                             style="width: 40px; height: 40px;">
                        <div>
                            <h5 class="mb-0">{{ $page?.props?.auth?.user?.username }}</h5>
                            <p class="text-muted small">{{ moment(new Date())?.format('lll') }}</p>
                        </div>
                    </div>
                    <textarea rows="5" v-model="post.description" class="form-control shadow-none border-0"
                              placeholder="What's on your mind?"></textarea>

                    <div class="d-flex ms-1 justify-content-between mt-1 mb-2">
                        <label for="inputImage"
                               class="form-label bg-light-primary px-4 py-1 rounded-3 text-muted d-flex align-items-center cursor-pointer">
                            <vue-feather type="image" size="18"/>
                            <span class="ms-1">Photo</span>
                            <input type="file" @input="handelImage" id="inputImage" class="d-none">
                        </label>
                    </div>

                    <!-- Post Image -->
                    <div class="mb-4" v-if="postImg">
                        <img :src="postImg" alt="Post Image" class="img-fluid rounded mb-3">
                    </div>
                    <button @click="submitPost" class="btn btn-primary w-100" :disabled="!isDisabled">Post</button>
                </div>
                <div class="card shadow-sm mt-4 p-2" v-for="blog in blogs?.data">
                    <!-- Post Header -->
                    <div class="d-flex justify-content-between mb-1">
                        <div class="d-flex gap-1 align-items-center">
                            <img :src="blog?.user?.photo_url" @error="handleImageError($event, blog?.user?.name)"
                                 alt="Profile" class="rounded-circle" style="width: 40px; height: 40px;">
                            <div>
                                <h5 class="mb-0">{{ blog?.user?.name }}</h5>
                                <p class="text-muted small">{{ moment(blog?.created_at)?.fromNow(true) }} ago</p>
                            </div>
                        </div>

                        <div class="position-relative d-flex items-center justify-content-center gap-1">
                            <!-- Up Arrow Button -->
                            <button class="btn btn-warning p-0"
                                    @click="updatePost(blog?.id)"
                                    v-if="!blog?.status"
                                    style="width: 30px; height: 30px;">
                                <vue-feather type="arrow-up" size="14"/>
                            </button>
                            <!-- Delete Button -->
                            <div class="rounded-circle waves-effect position-absolute offset-right-0 position_size">
                                <button type="button" class="btn rounded-full btn-primary" data-bs-toggle="dropdown"
                                        style="padding: 2px 5px"
                                        aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                         viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                         class="feather feather-more-vertical">
                                        <circle cx="12" cy="12" r="1"></circle>
                                        <circle cx="12" cy="5" r="1"></circle>
                                        <circle cx="12" cy="19" r="1"></circle>
                                    </svg>
                                </button>
                                <div class="dropdown-menu">
                                    <span @click="updateIsComment(blog?.id, blog?.is_like)"  class="dropdown-item d-flex align-items-center">
                                        <vue-feather :type="blog?.is_like ? 'arrow-up' : 'arrow-down'" size="16"/>
                                        <span class="ms-1">{{ blog?.is_like ? 'On' : 'Off' }} Comment</span>
                                    </span>
                                    <span class="dropdown-item" @click="deleteItemModal(blog.id)">
                                        <Icon title="trash"/>
                                       <span class="ms-1">Delete</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Post Content -->
                    <div v-html="blog?.description" class="mb-1 text-dark"></div>

                    <!-- Post Image -->
                    <div class="mb-4" v-if="blog?.image_url">
                        <img :src="blog?.image_url" alt="Post Image" class="img-fluid w-100 rounded">
                    </div>

                    <!-- Comment Section -->
                    <div class="border-top pt-3">
                        <div class="d-flex align-items-center gap-1 mb-3">
                            <img :src="$page?.props?.auth?.user?.photo" @error="handleImageError($event)"
                                 alt="Profile" class="rounded-circle" style="width: 32px; height: 32px;">
                            <div class="flex-grow-1 position-relative">
                                <input type="text" :disabled="blog?.is_like" v-model="comment.comment" class="form-control rounded-pill"
                                       placeholder="Write a comment...">
                                <button @click="saveComment(blog?.id)"
                                        :disabled="blog?.is_like"
                                        class="btn btn-primary btn-sm position-absolute"
                                        style="top: 3px;right: 3px;border-radius: 0px 30px 30px 0px;">
                                    <vue-feather type="send" style="rotate:45deg" size="14"></vue-feather>
                                </button>
                            </div>
                        </div>

                        <!-- Comments -->
                        <div v-if="blog?.comments?.length" class="mt-2 w-100">
                            <div class="d-flex gap-1 mb-3" v-for="comment in blog?.comments"
                                 :key="`s-coment-${comment?.id}`">
                                <img :src="comment?.user?.photo"
                                     @error="handleImageError($event)" alt="Profile"
                                     class="rounded-circle" style="width: 32px; height: 32px;">
                                <div class="w-100 bg-light">
                                    <div class="d-flex justify-content-between">
                                        <div class=" p-1 rounded w-100">
                                            <h6 class="font-semibold">{{ comment?.user?.name }}</h6>
                                            <p class="mb-0 small text-muted">{{ comment?.message }}</p>
                                            <p class="small text-muted">{{ moment(comment?.created_at)?.fromNow(true) }}
                                                ago</p>
                                        </div>

                                        <div>
                                            <button class="btn items-start btn-sm"
                                                    @click="deleteItem.deleteItem(props.url+'/delete-comments', comment.id)">
                                                <vue-feather type="trash-2" size="13" class="text-danger"/>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </Layout>


</template>

<script setup>
import Layout from "@/Shared/Layout.vue";
import moment from "moment"
import {router, useForm} from "@inertiajs/vue3";
import Swal from "sweetalert2";
import {ref, computed} from "vue";
import {useAction} from "@/Composable/useAction.js";
import Icon from "@/Components/Icon.vue";

const deleteItem = useAction();

const props = defineProps({
    blogs: {},
    url: String,
})

const post = useForm({
    description: null,
    image: null
})

const comment = useForm({
    comment: null
})
const postImg = ref(null)
const handelImage = (event) => {
    post.image = event.target.files[0]
    postImg.value = URL.createObjectURL(event.target.files[0])
}

const handleImageError = (event) => {
    event.target.src = '/image/image.png'
}
const isDisabled = computed(() => post.image || post.description)


const submitPost = () => {
    post.post(props.url, {
        onSuccess: () => {
            post.reset()
            post.description = null
            post.image = null
            postImg.value = null
        }
    })
}

const saveComment = (id) => {
    comment.post(props.url + '/save-comment/' + id, {
        onSuccess: () => {
            comment.reset()
            comment.comment = null
        }
    })
}

const updatePost = (id) => {
    router.post('/panel/update-status/' + id)
}

const updateIsComment = (id, status) => {
    router.get('/panel/update-comment-status/' + id+`?status=${!status}`)
}
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
</script>
