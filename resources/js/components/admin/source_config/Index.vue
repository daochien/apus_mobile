<template>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header border border-primary text-primary border-top-0 border-left-0 border-right-0">
                    <div class="row">
                        <div class="col-md-8">
                            Danh sách
                        </div>
                        <div class="col-md-4 text-right" >
                            <a class="btn btn-primary" href="/admin/source-configs/create">Tạo mới</a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table ">
                            <thead>
                            <tr>
                                <th class="border-top-0" scope="col">STT</th>
                                <th class="border-top-0" scope="col">Key</th>
                                <th class="border-top-0" scope="col">Group</th>
                                <th class="border-top-0" scope="col">Source</th>
                                <th class="border-top-0" scope="col">Tác vụ</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(item, index) in items.data" :key="index">
                                <td>{{ index + 1 }}</td>
                                <td>{{ item.key }}</td>
                                <td>{{ item.is_group? 'YES': 'NO' }}</td>
                                <td>
                                    {{ item.source.name }}
                                </td>

                                <td>
                                    <a :href="`/admin/source-configs/${item.id}`" type="button" class="btn btn-info btn-sm ">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm " @click="destroy(item.id)">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <pagination :limit="5" :data="items" @pagination-change-page="loadData" :align="'right'"></pagination>

                </div>
            </div>
        </div>
    </div>
</template>
<script>

const img_link ="/images/no_image.jpg";

export default {
    props: ['configs'],
    data () {
        return {
            items: {
                data: {}
            },
            imgDefault: img_link
        }
    },
    created () {
        this.items = this.configs;
    },
    methods: {
        loadData(page = 1) {
            this.pageCurrent = page;
            axios.get('/admin/source-configs?page='+page)
                .then(({data}) => {
                    this.items = data.data;
                })
        },
        destroy (id) {
            Swal.fire({
                title: 'Xóa source mẫu',
                text: 'Bạn có chắc chắn muốn xóa config này?',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Đồng ý',
                cancelButtonText: 'Hủy bỏ',
            }).then((result) => {
                if (result.value) {
                    axios.delete('/admin/source-configs/' + id).then(({data}) => {
                        let status = data.status ? 'success': 'error'
                        Toast.fire({
                            icon: status,
                            title: data.message,
                        });
                        this.loadData(this.pageCurrent);
                    }).catch((error) => {
                        Toast.fire({
                            icon: 'error',
                            title: 'Lỗi hệ thống',
                        });
                    });
                }
            })
        },
    }
}
</script>

