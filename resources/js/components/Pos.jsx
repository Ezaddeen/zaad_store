import React, { useEffect, useState, useCallback } from "react";
import axios from "axios";
import Swal from "sweetalert2";
import Cart from "./Cart";
import toast, { Toaster } from "react-hot-toast";
import CustomerSelect from "./CutomerSelect";

import SuccessSound from "../sounds/beep-07a.mp3";
import WarningSound from "../sounds/beep-02.mp3";
import playSound from "../utils/playSound";

export default function Pos() {
    const [products, setProducts] = useState([]);
    const [carts, setCarts] = useState([]);
    const [orderDiscount, setOrderDiscount] = useState(0);
    const [paid, setPaid] = useState(0);
    const [due, setDue] = useState(0);
    const [total, setTotal] = useState(0);
    const [updateTotal, setUpdateTotal] = useState(0);
    const [customerId, setCustomerId] = useState();
    const [cartUpdated, setCartUpdated] = useState(false);
    const [productUpdated, setProductUpdated] = useState(false);
    const [searchQuery, setSearchQuery] = useState("");
    const [searchBarcode, setSearchBarcode] = useState("");
    const { protocol, hostname, port } = window.location;
    const [currentPage, setCurrentPage] = useState(1);
    const [totalPages, setTotalPages] = useState(0);
    const [loading, setLoading] = useState(false);
    const fullDomainWithPort = `${protocol}//${hostname}${port ? `:${port}` : ""}`;

    // ==================================================
    // ⬇️            التعديل الأول: دالة getProducts           ⬇️
    // ==================================================
    const getProducts = useCallback(async (search = "", page = 1, barcode = "") => {
        setLoading(true);
        try {
            const res = await axios.get("/admin/get/products", {
                params: { search, page, barcode },
            });
            const productsData = res.data;
            
            if (page === 1) {
                setProducts(productsData.data);
            } else {
                setProducts((prev) => [...prev, ...productsData.data]);
            }
            
            if (productsData.data.length === 1 && barcode !== "") {
                addProductToCart(productsData.data[0].id);
            }
            setTotalPages(productsData.meta.last_page);
        } catch (error) {
            console.error("خطأ أثناء جلب المنتجات:", error);
        } finally {
            setLoading(false);
            // هذا السطر يقوم بتفريغ حقل الباركود بعد البحث
            if (barcode !== "") {
                setSearchBarcode("");
            }
        }
    }, []); // تم إزالة addProductToCart من هنا لتجنب حلقة لا نهائية محتملة مع useCallback

    const getUpdatedProducts = useCallback(async () => {
        try {
            const res = await axios.get("/admin/get/products");
            const productsData = res.data;
            setProducts(productsData.data);
            setTotalPages(productsData.meta.last_page);
        } catch (error) {
            console.error("خطأ أثناء تحديث المنتجات:", error);
        }
    }, []);

    useEffect(() => {
        getUpdatedProducts();
    }, [productUpdated]);

    const getCarts = async () => {
        try {
            const res = await axios.get("/admin/cart");
            const data = res.data;
            setTotal(data?.total || 0);
            setCarts(data?.carts || []);
        } catch (error) {
            console.error("خطأ أثناء جلب السلة:", error);
        }
    };

    useEffect(() => {
        getCarts();
    }, []);

    useEffect(() => {
        getCarts();
    }, [cartUpdated]);

    useEffect(() => {
        let paid1 = parseFloat(paid) || 0;
        let disc = parseFloat(orderDiscount) || 0;
        
        const updatedTotalAmount = parseFloat(total) - disc;
        const dueAmount = updatedTotalAmount - paid1;
        
        setUpdateTotal(updatedTotalAmount?.toFixed(2));
        setDue(dueAmount?.toFixed(2));
    }, [orderDiscount, paid, total]);
    
    // ==================================================
    // ⬇️        التعديل الثاني: دمج تأثيرات البحث         ⬇️
    // ==================================================
    useEffect(() => {
        // البحث بالاسم
        if (searchQuery) {
            setProducts([]);
            getProducts(searchQuery, 1, "");
            setSearchBarcode(""); // تفريغ حقل الباركود عند البحث بالاسم
        } 
        // البحث بالباركود
        else if (searchBarcode) {
            setProducts([]);
            getProducts("", 1, searchBarcode);
        }
        // الحالة الافتراضية (لا يوجد بحث)
        else {
             setProducts([]);
             getProducts("", 1, "");
        }
    }, [searchQuery, searchBarcode, getProducts]); // مراقبة كلا المتغيرين

    
    // منطق التمرير اللانهائي
    useEffect(() => {
        const handleScroll = () => {
             const productsContainer = document.querySelector('.products-card-container');
             const scrollElement = productsContainer ? productsContainer.parentElement : null;

             if (!scrollElement) return;

             if (scrollElement.scrollTop + scrollElement.clientHeight >= scrollElement.scrollHeight - 100) {
                 if (currentPage < totalPages && !loading) {
                     setCurrentPage((prev) => prev + 1);
                 }
             }
        };

        const container = document.querySelector('.products-card-container');
        if (container) {
            container.parentElement.addEventListener("scroll", handleScroll);
            return () => {
                 container.parentElement.removeEventListener("scroll", handleScroll);
            };
        }
    }, [currentPage, totalPages, loading]);


    function addProductToCart(id) {
        axios
            .post("/admin/cart", { id })
            .then((res) => {
                setCartUpdated(!cartUpdated);
                playSound(SuccessSound);
                toast.success(res?.data?.message || "تمت إضافة المنتج بنجاح"); 
            })
            .catch((err) => {
                playSound(WarningSound);
                toast.error(err.response.data.message || "حدث خطأ أثناء الإضافة إلى السلة"); 
            });
    }

    function cartEmpty() {
        if (total <= 0) return;
        Swal.fire({
            title: "هل أنت متأكد أنك تريد مسح السلة؟",
            showDenyButton: true,
            confirmButtonText: "نعم", 
            denyButtonText: "لا", 
            customClass: {
                actions: "my-actions",
                cancelButton: "order-1 right-gap",
                confirmButton: "order-2",
                denyButton: "order-3",
            },
        }).then((result) => {
            if (result.isConfirmed) {
                axios
                    .put("/admin/cart/empty")
                    .then((res) => {
                        setCartUpdated(!cartUpdated);
                        setOrderDiscount(0);
                        setPaid(0);
                        playSound(SuccessSound);
                        toast.success(res?.data?.message || "تم مسح السلة بنجاح"); 
                    })
                    .catch((err) => {
                        playSound(WarningSound);
                        toast.error(err.response.data.message || "حدث خطأ أثناء مسح السلة"); 
                    });
            }
        });
    }

    function orderCreate() {
        if (total <= 0) return;
        if (!customerId) {
            toast.error("يرجى اختيار العميل أولاً"); 
            return;
        }

        const formattedDue = parseFloat(due).toFixed(2);
        
        Swal.fire({
            title: `هل أنت متأكد أنك تريد إتمام هذا الطلب؟   
المتبقي: ${formattedDue}`,
            showDenyButton: true,
            confirmButtonText: "نعم", 
            denyButtonText: "لا", 
            customClass: {
                actions: "my-actions",
                cancelButton: "order-1 right-gap",
                confirmButton: "order-2",
                denyButton: "order-3",
            },
        }).then((result) => {
            if (result.isConfirmed) {
                axios
                    .put("/admin/order/create", {
                        customer_id: customerId,
                        order_discount: parseFloat(orderDiscount) || 0,
                        paid: parseFloat(paid) || 0,
                    })
                    .then((res) => {
                        setCartUpdated(!cartUpdated);
                        setProductUpdated(!productUpdated);
                        setOrderDiscount(0);
                        setPaid(0);
                        setTotal(0);
                        toast.success(res?.data?.message || "تم إنشاء الطلب بنجاح"); 
                        window.location.href = `orders/pos-invoice/${res?.data?.order?.id}`;
                    })
                    .catch((err) => {
                        toast.error(err.response.data.message || "حدث خطأ أثناء إنشاء الطلب");
                    });
            }
        });
    }

    return (
        <>
            <div className="card">
                <div className="card-body p-2 p-md-4 pt-0">
                    <div className="row">
                        <div className="col-md-6 col-lg-5 mb-2">
                            <div className="row mb-2">
                                <div className="col-12">
                                    <CustomerSelect setCustomerId={setCustomerId} />
                                </div>
                            </div>

                            <Cart
                                carts={carts}
                                setCartUpdated={setCartUpdated}
                                cartUpdated={cartUpdated}
                            />

                            <div className="card">
                                <div className="card-body">
                                    <div className="row text-bold mb-1">
                                        <div className="col">المجموع الجزئي:</div> 
                                        <div className="col text-right mr-2">{total}</div>
                                    </div>
                                    <div className="row text-bold mb-1">
                                        <div className="col">الخصم:</div> 
                                        <div className="col text-right mr-2">
                                            <input
                                                type="number"
                                                className="form-control form-control-sm"
                                                placeholder="أدخل قيمة الخصم" 
                                                min={0}
                                                disabled={total <= 0}
                                                value={orderDiscount}
                                                onChange={(e) => {
                                                    const value = e.target.value;
                                                    if (
                                                        parseFloat(value) > total ||
                                                        parseFloat(value) < 0
                                                    ) {
                                                        return;
                                                    }
                                                    setOrderDiscount(value);
                                                }}
                                            />
                                        </div>
                                    </div>
                                    <div className="row text-bold mb-1">
                                        <div className="col">تطبيق خصم الكسور:</div>
                                        <div className="col text-right mr-2">
                                            <input
                                                type="checkbox"
                                                className="form-control-sm"
                                                disabled={total <= 0}
                                                onChange={(e) => {
                                                    if (e.target.checked) {
                                                        const fractionalPart = total % 1;
                                                        setOrderDiscount(fractionalPart?.toFixed(2));
                                                    } else {
                                                        setOrderDiscount(0);
                                                    }
                                                }}
                                            />
                                        </div>
                                    </div>
                                    <div className="row text-bold mb-1">
                                        <div className="col">الإجمالي:</div> 
                                        <div className="col text-right mr-2">{updateTotal}</div>
                                    </div>
                                    <div className="row text-bold mb-1">
                                        <div className="col">المدفوع:</div> 
                                        <div className="col text-right mr-2">
                                            <input
                                                type="number"
                                                className="form-control form-control-sm"
                                                placeholder="أدخل المبلغ المدفوع"
                                                min={0}
                                                disabled={total <= 0}
                                                value={paid}
                                                onChange={(e) => {
                                                    const value = e.target.value;
                                                    if (
                                                        parseFloat(value) < 0 ||
                                                        parseFloat(value) > updateTotal
                                                    ) {
                                                        return;
                                                    }
                                                    setPaid(value);
                                                }}
                                            />
                                        </div>
                                    </div>
                                    <div className="row text-bold">
                                        <div className="col">المتبقي:</div> 
                                        <div className="col text-right mr-2">{due}</div>
                                    </div>
                                </div>
                            </div>

                            <div className="row mt-3">
                                <div className="col">
                                    <button
                                        onClick={() => cartEmpty()}
                                        type="button"
                                        className="btn bg-gradient-danger btn-block text-white text-bold"
                                        disabled={total <= 0}
                                    >
                                        مسح السلة
                                    </button>
                                </div>
                                <div className="col">
                                    <button
                                        onClick={() => orderCreate()}
                                        type="button"
                                        className="btn bg-gradient-primary btn-block text-white text-bold"
                                        disabled={total <= 0}
                                    >
                                        إتمام الطلب
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div className="col-md-6 col-lg-7">
                            <div className="row">
                                <div className="input-group mb-2 col-md-6">
                                    <div className="input-group-prepend">
                                        <span className="input-group-text">
                                            <i className="fas fa-barcode"></i>
                                        </span>
                                    </div>
                                    <input
                                        type="text"
                                        className="form-control"
                                        placeholder="أدخل باركود المنتج" 
                                        value={searchBarcode}
                                        autoFocus
                                        onChange={(e) => setSearchBarcode(e.target.value)}
                                    />
                                </div>
                                <div className="mb-2 col-md-6">
                                    <input
                                        type="text"
                                        className="form-control"
                                        placeholder="أدخل اسم المنتج" 
                                        value={searchQuery}
                                        onChange={(e) => setSearchQuery(e.target.value)}
                                    />
                                </div>
                            </div>

                            <div className="row products-card-container">
                                {products.length > 0 &&
                                    products.map((product, index) => (
                                        <div
                                            onClick={() => addProductToCart(product.id)}
                                            className="col-6 col-md-4 col-lg-3 mb-3"
                                            key={index}
                                            style={{ cursor: "pointer" }}
                                        >
                                            <div className="text-center">
                                                <img
                                                    src={`${fullDomainWithPort}/storage/${product.image}`}
                                                    alt={product.name}
                                                    className="mr-2 img-thumb"
                                                    onError={(e) => {
                                                        e.target.onerror = null;
                                                        e.target.src = `${fullDomainWithPort}/assets/images/no-image.png`;
                                                    }}
                                                    width={120}
                                                    height={100}
                                                />
                                                <div className="product-details">
                                                    <p className="mb-0 text-bold product-name">
                                                        {product.name} ({product.quantity})
                                                    </p>
                                                    <p>السعر: {product?.discounted_price}</p> 
                                                </div>
                                            </div>
                                        </div>
                                    ))}
                            </div>

                            {loading && (
                                <div className="loading-more text-center mt-2">
                                    يتم تحميل المزيد...
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </div>
            <Toaster position="top-right" reverseOrder={false} />
        </>
    );
}
