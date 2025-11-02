import axios from "axios";
import React from "react";
import toast, { Toaster } from "react-hot-toast";
import Swal from "sweetalert2";
import SuccessSound from "../sounds/beep-07a.mp3";
import WarningSound from "../sounds/beep-02.mp3";
import playSound from "../utils/playSound";

export default function Cart({ carts, setCartUpdated, cartUpdated }) {
    function increment(id) {
        axios
            .put("/admin/cart/increment", { id })
            .then((res) => {
                setCartUpdated(!cartUpdated);
                playSound(SuccessSound);
                toast.success(res?.data?.message || "تمت زيادة الكمية بنجاح"); // تم إزالة النجمات
            })
            .catch((err) => {
                playSound(WarningSound);
                toast.error(err.response?.data?.message || "حدث خطأ أثناء زيادة الكمية"); // تم إزالة النجمات
            });
    }

    function decrement(id) {
        axios
            .put("/admin/cart/decrement", { id })
            .then((res) => {
                setCartUpdated(!cartUpdated);
                playSound(SuccessSound);
                toast.success(res?.data?.message || "تم تقليل الكمية بنجاح"); // تم إزالة النجمات
            })
            .catch((err) => {
                playSound(WarningSound);
                toast.error(err.response?.data?.message || "حدث خطأ أثناء تقليل الكمية"); // تم إزالة النجمات
            });
    }

    function destroy(id) {
        Swal.fire({
            title: "هل أنت متأكد أنك تريد حذف هذا المنتج؟", // تم إزالة النجمات
            showDenyButton: true,
            confirmButtonText: "نعم", // تم إزالة النجمات
            denyButtonText: "لا", // تم إزالة النجمات
            customClass: {
                actions: "my-actions",
                cancelButton: "order-1 right-gap",
                confirmButton: "order-2",
                denyButton: "order-3",
            },
        }).then((result) => {
            if (result.isConfirmed) {
                axios
                    .put("/admin/cart/delete", { id })
                    .then((res) => {
                        setCartUpdated(!cartUpdated);
                        playSound(SuccessSound);
                        toast.success(res?.data?.message || "تم حذف المنتج بنجاح"); // تم إزالة النجمات
                    })
                    .catch((err) => {
                        playSound(WarningSound);
                        toast.error(err.response?.data?.message || "حدث خطأ أثناء الحذف"); // تم إزالة النجمات
                    });
            }
        });
    }

    return (
        <>
            <style>
                {`
                .user-cart {
                    direction: rtl;
                    text-align: right;
                }
                .user-cart table {
                    direction: rtl;
                }
                .user-cart th, .user-cart td {
                    text-align: center;
                    vertical-align: middle;
                }
                `}
            </style>

            <div className="user-cart">
                <div className="card">
                    <div className="card-body">
                        <div className="responsive-table">
                            <table className="table table-striped text-center">
                                <thead>
                                    <tr>
                                        {/* الترتيب من اليمين لليسار */}
                                        <th>المنتج</th> {/* تم إزالة النجمات */}
                                        <th>الكمية</th> {/* تم إزالة النجمات */}
                                        <th>السعر</th> {/* تم إزالة النجمات */}
                                        <th>الإجمالي</th> {/* تم إزالة النجمات */}
                                        <th>إجراء</th> {/* تم إزالة النجمات */}
                                    </tr>
                                </thead>
                                <tbody>
                                    {carts.map((item) => (
                                        <tr key={item.id}>
                                            {/* المنتج (الاسم) */}
                                            <td>{item.product.name}</td>
                                            
                                            {/* الكمية (مع الأزرار) */}
                                            <td className="d-flex align-items-center justify-content-center">
                                                <button
                                                    className="btn btn-warning btn-sm"
                                                    onClick={() => decrement(item.id)}
                                                    title="تقليل الكمية"
                                                >
                                                    <i className="fas fa-minus"></i>
                                                </button>
                                                <input
                                                    type="number"
                                                    className="form-control form-control-sm qty mx-2 text-center"
                                                    value={item.quantity}
                                                    disabled
                                                />
                                                <button
                                                    className="btn btn-success btn-sm"
                                                    onClick={() => increment(item.id)}
                                                    title="زيادة الكمية"
                                                >
                                                    <i className="fas fa-plus"></i>
                                                </button>
                                            </td>

                                            {/* السعر */}
                                            <td className="text-right">
                                                {item?.product?.discounted_price} ر.س {/* تم إزالة النجمات */}
                                                {item?.product?.price > item?.product?.discounted_price && (
                                                    <>
                                                        <br />
                                                        <del>{item?.product?.price} ر.س</del> {/* تم إزالة النجمات */}
                                                    </>
                                                )}
                                            </td>
                                            
                                            {/* الإجمالي */}
                                            <td className="text-right">
                                                {item?.row_total} ر.س {/* تم إزالة النجمات */}
                                            </td>

                                            {/* الإجراء (الحذف) */}
                                            <td>
                                                <button
                                                    className="btn btn-danger btn-sm"
                                                    onClick={() => destroy(item.id)}
                                                    title="حذف المنتج"
                                                >
                                                    <i className="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                            {carts.length === 0 && (
                                <p className="text-center mt-3">لا توجد منتجات في السلة</p> // تم إزالة النجمات
                            )}
                        </div>
                    </div>
                </div>
            </div>

            <Toaster position="top-right" reverseOrder={false} />
        </>
    );
}