<template>
    <div>
        <h2>
            Users
        </h2>
        <form @submit.prevent="addUser" class="mb-3">
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Name" v-model="user.name">
            </div>
            <div class="form-group">
                <input type="email" class="form-control" placeholder="EMail" v-model="user.email">
            </div>
            <button type="submit" class="btn btn-info btn-block">Save</button>
        </form>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item">
                    <a class="page-link" href="#" @click="fetchUsers(pagination.prev_page_url)">Previous</a>
                </li>
                <li class="page-item disabled"><a class="page-link test-dark" href="#">Page {{ pagination.current_page
                    }} of {{ pagination.last_page }}</a></li>
                <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item">
                    <a class="page-link" href="#" @click="fetchUsers(pagination.next_page_url)">Next</a>
                </li>
            </ul>
        </nav>
        <div class="card card-body mb-2" v-for="user in users" v-bind:key="user.id">
            <h3>{{ user.name }}</h3>
            <p>{{ user.email }}</p>
            <hr>
            <button @click="editUser(user)" class="btn btn-warning mb-2">Edit</button>
            <button @click="deleteUser(user.id)" class="btn btn-danger">Delete</button>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                users: [],
                user: {
                    id: '',
                    name: '',
                    email: '',
                    password: ''
                },
                user_id: '',
                pagination: {},
                edit: false
            }
        },

        created() {
            this.fetchUsers();
        },

        methods: {
            fetchUsers(page_url) {
                let vm = this;
                page_url = page_url || 'api/users'
                fetch(page_url)
                    .then(res => res.json())
                    .then(res => {
                        this.users = res.data;
                        vm.makePagination(res.meta, res.links);
                    })
                    .catch(err => console.log(err));
            },
            makePagination(meta, links) {
                let pagination = {
                    current_page: meta.current_page,
                    last_page: meta.last_page,
                    next_page_url: links.next,
                    prev_page_url: links.prev
                }
                this.pagination = pagination;
            },
            deleteUser(id) {
                if (confirm('Are you sure?')) {
                    fetch("api/deleteUser/" + id, {
                        method: 'delete'
                    })
                        .then(res => res.json())
                        .then(data => {
                            alert('User deleted successfully.');
                            this.fetchUsers();
                        })
                        .catch(err => console.log(err));
                }
            },
            addUser() {
                if (this.edit === false) {
                    //Add
                    fetch('api/addUser', {
                        method: 'post',
                        body: JSON.stringify(this.user),
                        headers: {
                            'content-type': 'application/json'
                        }
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data.message) {
                                alert(data.message);
                            } else {
                                this.user.name = '';
                                this.user.email = '';
                                alert('User Added Successfully');
                            }
                            this.fetchUsers();
                        })
                } else {
                    //Update
                    fetch('api/editUser', {
                        method: 'put',
                        body: JSON.stringify(this.user),
                        headers: {
                            'content-type': 'application/json'
                        }
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data.message) {
                                alert(data.message);
                            } else {
                                this.user.name = '';
                                this.user.email = '';
                                alert('User Updated Successfully');
                            }
                            this.fetchUsers();
                        })
                }
            },
            editUser(user) {
                this.edit = true;
                this.user.id = user.id;
                this.user.user_id = user.id;
                this.user.name = user.name;
                this.user.email = user.email;
            }
        }
    }
</script>
