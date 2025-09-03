export default [
	{
		name: "payment_type",
		label: "Enter your payment type",
		type: "select",
		label: "Select payment type",
		multiple: false,
		data_list: [
			{
				label: "Offline",
				value: "offline",
			},
			{
				label: "Online",
				value: "online",
			},
		],
		value: "",
	},

	{
		name: "payment_by",
		label: "Enter your payment by",
		type: "text",
		value: "",
	},

	{
		name: "receipt_id",
		label: "Enter your receipt id",
		type: "text",
		value: "",
	},

	{
		name: "trx_id",
		label: "Enter your trx id",
		type: "text",
		value: "",
	},

	{
		name: "payment_status",
		label: "Enter your payment status",
		type: "select",
		label: "Select payment status",
		multiple: false,
		data_list: [
			{
				label: "Paid",
				value: "paid",
			},
			{
				label: "Unpaid",
				value: "unpaid",
			},
			{
				label: "Failed",
				value: "failed",
			},
		],
		value: "",
	},

	{
		name: "total_amount",
		label: "Enter your total amount",
		type: "number",
		step: "0.01",
		value: "",
	},

	{
		name: "paid_amount",
		label: "Enter your paid amount",
		type: "number",
		step: "0.01",
		value: "",
	},
];
