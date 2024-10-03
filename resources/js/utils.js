const getOrderStatusColor = (order) => {
    switch (order.status) {
        case "WAITING":
            return "bg-yellow-500";
        case "PRODUCTION":
            return "bg-orange-500";
        case "SUBMITTED":
            return "bg-purple-500";
        case "PROCESSING":
            return "bg-blue-500";
        case "PUBLISHED":
            return "bg-pink-500";
        case "INVOICE":
            return "bg-sky-500";
        case "COMPLETED":
            return "bg-green-500";
        default:
            return "";
    }
};

export { getOrderStatusColor };
