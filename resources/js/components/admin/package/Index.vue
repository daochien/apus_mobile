<template>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header border border-primary text-primary border-top-0 border-left-0 border-right-0">
                    <div class="row">
                        <div class="col-md-8">
                            Danh sách souce mẫu
                        </div>
                        <div class="col-md-4 text-right" >
                            <a class="btn btn-primary" href="/admin/packages/create">Tạo mới</a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table ">
                            <thead>
                            <tr>
                                <th class="border-top-0" scope="col">STT</th>
                                <th class="border-top-0" scope="col">Ảnh dại diện</th>
                                <th class="border-top-0" scope="col">Code</th>
                                <th class="border-top-0" scope="col">Tên</th>
                                <th class="border-top-0" scope="col">Giá</th>
                                <th class="border-top-0" scope="col">Trạng thái</th>
                                <th class="border-top-0" scope="col">Tác vụ</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(item, index) in items.data" :key="index">
                                <td>{{ index + 1 }}</td>

                                <td>
                                    <img v-if="item.avatar" :src="item.avatar" width="75" height="75" >
                                    <img v-else :src="imgDefault" width="75" height="75" >
                                </td>
                                <td>{{ item.code }}</td>
                                <td>{{ item.name }}</td>
                                <td>
                                    {{ item.path }}
                                </td>
                                <td>
                                    <span v-if="item.status" class="label label-sm bg-success text-white mr-1">
                                        Có
                                    </span>
                                    <span v-else class="label label-sm bg-danger text-white mr-1">
                                        Không
                                    </span>
                                </td>

                                <td>
                                    <a :href="`/admin/sources/${item.id}`" type="button" class="btn btn-info btn-sm ">
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
    props: ['packages'],
    data () {
        return {
            items: {
                data: {}
            },
            imgDefault: img_link
        }
    },
    created () {
        this.items = this.packages;
    },
    methods: {
        loadData(page = 1) {
            this.pageCurrent = page;
            axios.get('/admin/packages?page='+page)
                .then(({data}) => {
                    this.items = data.data;
                })
        },
        destroy (id) {
            Swal.fire({
                title: 'Xóa source mẫu',
                text: 'Bạn có chắc chắn muốn xóa source mẫu này?',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Đồng ý',
                cancelButtonText: 'Hủy bỏ',
            }).then((result) => {
                if (result.value) {
                    axios.delete('/admin/packages/' + id).then(({data}) => {
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
