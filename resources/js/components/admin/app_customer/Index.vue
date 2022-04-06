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
                            <a class="btn btn-primary" href="/admin/app-customers/create">Tạo mới</a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table ">
                            <thead>
                            <tr>
                                <th class="border-top-0" scope="col">STT</th>
                                <th class="border-top-0" scope="col">Code</th>
                                <th class="border-top-0" scope="col">Thông tin khách hàng</th>
                                <th class="border-top-0" scope="col">Thông tin gói</th>
                                <th class="border-top-0" scope="col">Ngày hết hạn</th>
                                <th class="border-top-0" scope="col">Trạng thái</th>
                                <th class="border-top-0" scope="col">Tác vụ</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(item, index) in items.data" :key="index">
                                <td>{{ index + 1 }}</td>
                                <td>{{ item.code }}</td>
                                <td>
                                    <span>
                                        {{ item.customer_name }} <br>
                                        {{ item.customer_phone }} <br>
                                        {{ item.customer_email }}
                                    </span>
                                </td>
                                <td>
                                    <span v-if="item.package">
                                        {{ item.package.name }} <br>
                                        {{ item.package.price | numberFormat }}
                                    </span>
                                </td>
                                <td>
                                    {{ item.expired | formatTimeLogUnix }}
                                </td>
                                <td>
                                    <span v-if="item.status" class="label label-sm bg-success text-white mr-1">
                                        Active
                                    </span>
                                    <span v-else class="label label-sm bg-danger text-white mr-1">
                                        InActive
                                    </span>
                                </td>

                                <td>
                                    <a :href="`/admin/app-customers/${item.id}`" type="button" class="btn btn-info btn-sm ">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a type="button" class="btn btn-info btn-sm " @click="download(item.code)">
                                        <i class="fas fa-file-download"></i>
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
    props: ['apps'],
    data () {
        return {
            items: {
                data: {}
            },
            imgDefault: img_link
        }
    },
    created () {
        this.items = this.apps;
    },
    methods: {
        loadData(page = 1) {
            this.pageCurrent = page;
            axios.get('/admin/app-customers?page='+page)
                .then(({data}) => {
                    this.items = data.data;
                })
        },
        destroy (id) {
            Swal.fire({
                title: 'Xóa app khách hàng',
                text: 'Bạn có chắc chắn muốn xóa app khách hàng này?',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Đồng ý',
                cancelButtonText: 'Hủy bỏ',
            }).then((result) => {
                if (result.value) {
                    axios.delete('/admin/app-customers/' + id).then(({data}) => {
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
        download (code) {
            window.open('/admin/app-customers/download/'+code, '_blank');
        }
    }
}
</script>
