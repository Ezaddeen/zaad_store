import React, { useCallback, useEffect, useState } from "react";
import Suppliers from "./Suppliers";
import axios from "axios";
import Swal from "sweetalert2";
import toast, { Toaster } from "react-hot-toast";
import DatePicker from "react-datepicker";
import "react-datepicker/dist/react-datepicker.css";

export default function Purchase() {
    const [searchTerm, setSearchTerm] = useState("");
    const [barcode, setBarcode] = useState("");
    const [selectedSupplier, setSelectedSupplier] = useState({
        value: 1,
        label: "المورّد الافتراضي",
    });
    const [purchaseId, setPurchaseId] = useState(null);
    const [date, setDate] = useState(null);
    const [supplierId, setSupplierId] = useState(null);
    const [tax, setTax] = useState(0);
    const [discount, setDiscount] = useState(0);
    const [shipping, setShipping] = useState(0);
    const [products, setProducts] = useState([]);
    const [searchResults, setSearchResults] = useState([]);

    useEffect(() => {
        const searchParams = new URLSearchParams(window.location.search);
        const barcodeParam = searchParams.get("barcode");
        const purchase_id = searchParams.get("purchase_id");
        if (barcodeParam) {
            setSearchTerm(barcodeParam);
            setBarcode(barcodeParam);
        }
        if (purchase_id) {
            setPurchaseId(purchase_id);
        }
    }, []);

    useEffect(() => {
        if (barcode) {
            getProducts();
        }
    }, [barcode]);

    useEffect(() => {
        if (purchaseId) {
            getPurchaseProducts();
        }
    }, [purchaseId]);

    const getPurchaseProducts = useCallback(async () => {
        try {
            const res = await axios.get(`/admin/purchase/${purchaseId}`);
            const purchaseData = res.data;
            const purchaseProducts = purchaseData?.items?.map((item) => ({
                item_id: item.id,
                id: item.product_id,
                name: item.name,
                price: item.price,
                purchase_price: item.purchase_price,
                stock: item.stock,
                qty: item.quantity,
                subTotal: item.purchase_price * item.quantity,
            }));
            setProducts(purchaseProducts);
            setDate(purchaseData?.date ? purchaseData.date.split(" ")[0] : "");
            setSelectedSupplier({
                value: purchaseData?.supplier_id,
                label: purchaseData?.supplier?.name,
            });
            setTax(purchaseData?.tax);
            setDiscount(purchaseData?.discount_value);
            setShipping(purchaseData?.shipping);
        } catch (error) {
            console.error("حدث خطأ أثناء جلب المنتجات:", error);
        }
    }, [purchaseId]);

    const getProducts = useCallback(async () => {
        if (!searchTerm.trim()) {
            console.log("حقل البحث فارغ");
            return;
        }

        try {
            const res = await axios.get("/admin/products", {
                params: { search: searchTerm },
            });

            const productsData = res.data;

            if (productsData?.data && productsData.data.length) {
                productsData.data.forEach((product) => {
                    const existingProductIndex = products.findIndex(
                        (p) => p.id === product.id
                    );
                    if (existingProductIndex !== -1) {
                        setProducts((prevProducts) => {
                            const updatedProducts = [...prevProducts];
                            updatedProducts[existingProductIndex].qty += 1;
                            updatedProducts[existingProductIndex].subTotal =
                                updatedProducts[existingProductIndex]
                                    .purchase_price *
                                updatedProducts[existingProductIndex].qty;
                            return updatedProducts;
                        });
                    } else {
                        const newProduct = {
                            id: product.id,
                            name: product.name,
                            price: product.price,
                            purchase_price: product.purchase_price,
                            stock: product.quantity,
                            qty: 1,
                            subTotal: product.purchase_price,
                        };
                        setProducts((prevProducts) => [
                            ...prevProducts,
                            newProduct,
                        ]);
                    }
                });
            }
        } catch (error) {
            console.error("حدث خطأ أثناء جلب المنتجات:", error);
        } finally {
            setSearchTerm("");
        }
    }, [searchTerm]);

    const handleDelete = (id) => {
        setProducts(products.filter((product) => product.id !== id));
    };

    const handleQtyChange = (id, value) => {
        const updatedProducts = products.map((product) => {
            if (product.id === id) {
                const newQty = parseInt(value) || 0;
                return {
                    ...product,
                    qty: newQty,
                    subTotal: parseFloat(
                        (product.purchase_price * newQty).toFixed(2)
                    ),
                };
            }
            return product;
        });
        setProducts(updatedProducts);
    };

    const handlePriceChange = (id, value) => {
        const updatedProducts = products.map((product) => {
            if (product.id === id) {
                const newPrice = parseFloat(value) || 0;
                return {
                    ...product,
                    purchase_price: newPrice,
                    subTotal: parseFloat((product.qty * newPrice).toFixed(2)),
                };
            }
            return product;
        });
        setProducts(updatedProducts);
    };

    const handleSearchAdd = () => {
        getProducts();
    };

    const calculateTotals = () => {
        const subTotal = products.reduce(
            (sum, product) => sum + product.subTotal,
            0
        );
        const formattedSubTotal = parseFloat(subTotal.toFixed(2));
        const formattedTax = parseFloat((tax || 0).toFixed(2));
        const formattedDiscount = parseFloat((discount || 0).toFixed(2));
        const formattedShipping = parseFloat((shipping || 0).toFixed(2));
        const grandTotal = parseFloat(
            (
                formattedSubTotal +
                formattedTax -
                formattedDiscount +
                formattedShipping
            ).toFixed(2)
        );

        return {
            subTotal: formattedSubTotal,
            tax: formattedTax,
            discount: formattedDiscount,
            shipping: formattedShipping,
            grandTotal,
        };
    };

    const totals = calculateTotals();

    const handleSubmit = async () => {
        if (totals.grandTotal <= 0) {
            toast.error("يجب أن يكون الإجمالي أكبر من الصفر");
            return;
        }
        if (!date) {
            toast.error("يرجى تحديد تاريخ الشراء");
            return;
        }
        if (!supplierId) {
            toast.error("يرجى اختيار المورّد");
            return;
        }

        Swal.fire({
            title: "هل تريد حفظ عملية الشراء؟",
            showDenyButton: true,
            confirmButtonText: "نعم",
            denyButtonText: "لا",
            customClass: {
                actions: "my-actions",
                cancelButton: "order-1 right-gap",
                confirmButton: "order-2",
                denyButton: "order-3",
            },
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    const res = await axios.post("/admin/purchase", {
                        purchase_id: purchaseId,
                        date,
                        products,
                        supplierId,
                        totals,
                    });
                    setProducts([]);
                    toast.success(res?.data?.message || "تم حفظ عملية الشراء بنجاح");
                    window.location.href = "/admin/purchase";
                } catch (err) {
                    toast.error(
                        err.response?.data?.message || "حدث خطأ أثناء حفظ عملية الشراء"
                    );
                }
            }
        });
    };

    useEffect(() => {
        async function getProducts() {
            if (!searchTerm.trim()) {
                setSearchResults([]);
                return;
            }
            try {
                const res = await axios.get("/admin/products", {
                    params: { search: searchTerm },
                });
                const productsData = res.data;
                setSearchResults(productsData?.data || []);
            } catch (error) {
                console.error("حدث خطأ أثناء جلب المنتجات:", error);
            }
        }
        getProducts();
    }, [searchTerm]);

    const handleProductSelect = (product) => {
        const existingProductIndex = products.findIndex(
            (p) => p.id === product.id
        );

        if (existingProductIndex !== -1) {
            setProducts((prevProducts) => {
                const updatedProducts = [...prevProducts];
                updatedProducts[existingProductIndex].qty += 1;
                updatedProducts[existingProductIndex].subTotal =
                    updatedProducts[existingProductIndex].purchase_price *
                    updatedProducts[existingProductIndex].qty;
                return updatedProducts;
            });
        } else {
            const newProduct = {
                id: product.id,
                name: product.name,
                price: product.price,
                purchase_price: product.purchase_price,
                stock: product.quantity,
                qty: 1,
                subTotal: product.purchase_price,
            };
            setProducts((prevProducts) => [...prevProducts, newProduct]);
        }

        setSearchTerm("");
        setSearchResults([]);
    };

    return (
        <>
            <div className="container-fluid">
                <div className="card">
                    <div className="card-body">
                        <div className="row">
                            <div className="mb-3 col-md-6">
                                <label htmlFor="date" className="form-label">
                                    تاريخ الشراء<span className="text-danger">*</span>
                                </label>
                                <div>
                                    <DatePicker
                                        name="date"
                                        className="form-control"
                                        placeholderText="أدخل تاريخ الشراء"
                                        selected={date}
                                        dateFormat="yyyy-MM-dd"
                                        onChange={(date) => {
                                            const formattedDate = date
                                                ? date
                                                      .toISOString()
                                                      .split("T")[0]
                                                : null;
                                            setDate(formattedDate);
                                        }}
                                    />
                                </div>
                            </div>
                            <div className="mb-3 col-md-6">
                                <label htmlFor="supplier" className="form-label">
                                    المورّد<span className="text-danger">*</span>
                                </label>
                                <Suppliers
                                    setSupplierId={setSupplierId}
                                    oldSupplier={selectedSupplier}
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <div className="card">
                    <div className="card-body">
                        <div className="row mb-2">
                            <div className="input-group col-6">
                                <div className="input-group-prepend">
                                    <span className="input-group-text">
                                        <i className="fas fa-search"></i>
                                    </span>
                                </div>
                                <input
                                    type="search"
                                    className="form-control form-control-lg"
                                    value={searchTerm}
                                    onChange={(e) =>
                                        setSearchTerm(e.target.value)
                                    }
                                    placeholder="أدخل باركود أو اسم المنتج"
                                />
                                <button
                                    className="btn bg-gradient-primary mr-2"
                                    onClick={handleSearchAdd}
                                >
                                    إضافة منتج
                                </button>
                            </div>
                        </div>

                        {searchResults.length > 0 && (
                            <div className="row mb-2">
                                <div
                                    className="col-6"
                                    style={{
                                        maxHeight: "200px",
                                        overflowY: "auto",
                                    }}
                                >
                                    <ul className="list-group">
                                        {searchResults.map((product) => (
                                            <li
                                                key={product.id}
                                                className="list-group-item"
                                                onClick={() =>
                                                    handleProductSelect(product)
                                                }
                                                style={{ cursor: "pointer" }}
                                            >
                                                {product.name} - {product.price} ر.س
                                            </li>
                                        ))}
                                    </ul>
                                </div>
                            </div>
                        )}

                        <div className="row">
                            <div className="col-12">
                                <table className="table table-sm table-bordered text-center">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>اسم المنتج</th>
                                            <th>سعر الشراء</th>
                                            <th>المخزون الحالي</th>
                                            <th>الكمية</th>
                                            <th>الإجمالي الفرعي</th>
                                            <th>الإجراء</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {products.map((product, index) => (
                                            <tr key={product.id}>
                                                <td>{index + 1}</td>
                                                <td>{product.name}</td>
                                                <td className="d-flex align-items-center justify-content-center">
                                                    <input
                                                        type="number"
                                                        min="1"
                                                        className="form-control w-50"
                                                        value={
                                                            product.purchase_price
                                                        }
                                                        onChange={(e) =>
                                                            handlePriceChange(
                                                                product.id,
                                                                e.target.value
                                                            )
                                                        }
                                                    />
                                                </td>
                                                <td>{product.stock}</td>
                                                <td className="d-flex align-items-center justify-content-center">
                                                    <input
                                                        type="number"
                                                        min="1"
                                                        className="form-control w-50"
                                                        value={product.qty}
                                                        onChange={(e) =>
                                                            handleQtyChange(
                                                                product.id,
                                                                e.target.value
                                                            )
                                                        }
                                                    />
                                                </td>
                                                <td>
                                                    {product.subTotal.toFixed(2)}
                                                </td>
                                                <td>
                                                    <button
                                                        className="btn btn-danger btn-sm"
                                                        onClick={() =>
                                                            handleDelete(
                                                                product.id
                                                            )
                                                        }
                                                    >
                                                        حذف
                                                    </button>
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div className="row">
                            <div className="col-6"></div>
                            <div className="col-6">
                                <div className="table-responsive">
                                    <table className="table table-sm">
                                        <tbody>
                                            <tr>
                                                <th>الإجمالي الفرعي:</th>
                                                <td className="text-right">
                                                    {totals.subTotal.toFixed(2)}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>الضريبة:</th>
                                                <td className="text-right">
                                                    {totals.tax.toFixed(2)}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>الخصم:</th>
                                                <td className="text-right">
                                                    {totals.discount.toFixed(2)}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>رسوم الشحن:</th>
                                                <td className="text-right">
                                                    {totals.shipping.toFixed(2)}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>الإجمالي الكلي:</th>
                                                <td className="text-right">
                                                    {totals.grandTotal.toFixed(2)}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div className="card">
                    <div className="card-body">
                        <div className="row">
                            <div className="mb-3 col-md-4">
                                <label htmlFor="tax" className="form-label">
                                    الضريبة
                                </label>
                                <input
                                    type="number"
                                    className="form-control"
                                    value={tax}
                                    min="0"
                                    onChange={(e) =>
                                        setTax(parseFloat(e.target.value) || 0)
                                    }
                                    placeholder="أدخل قيمة الضريبة"
                                    name="tax"
                                    required
                                />
                            </div>
                            <div className="mb-3 col-md-4">
                                <label htmlFor="discount" className="form-label">
                                    الخصم
                                </label>
                                <input
                                    type="number"
                                    min="0"
                                    className="form-control"
                                    value={discount}
                                    onChange={(e) =>
                                        setDiscount(
                                            parseFloat(e.target.value) || 0
                                        )
                                    }
                                    placeholder="أدخل قيمة الخصم"
                                    name="discount"
                                    required
                                />
                            </div>
                            <div className="mb-3 col-md-4">
                                <label htmlFor="shipping" className="form-label">
                                    رسوم الشحن
                                </label>
                                <input
                                    type="number"
                                    min="0"
                                    className="form-control"
                                    value={shipping}
                                    onChange={(e) =>
                                        setShipping(
                                            parseFloat(e.target.value) || 0
                                        )
                                    }
                                    placeholder="أدخل رسوم الشحن"
                                    name="shipping"
                                    required
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <button
                    type="submit"
                    className="btn btn-md bg-gradient-primary"
                    onClick={handleSubmit}
                >
                    {purchaseId ? "تحديث الشراء" : "إنشاء عملية شراء"}
                </button>
            </div>

            <Toaster position="top-right" reverseOrder={false} />
        </>
    );
}
