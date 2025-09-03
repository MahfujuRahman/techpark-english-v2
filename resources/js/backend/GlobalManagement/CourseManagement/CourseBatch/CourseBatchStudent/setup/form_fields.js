export default [
	{
		name: "course_id",
		label: "Enter your course id",
		type: "number",
		value: "",
	},

	{
		name: "batch_id",
		label: "Enter your batch id",
		type: "number",
		value: "",
	},

	{
		name: "student_id",
		label: "Enter your student id",
		type: "number",
		value: "",
	},

	{
		name: "course_percent",
		label: "Enter your course percent",
		type: "text",
		value: "",
	},

	{
		name: "is_complete",
		label: "Enter your is complete",
		type: "select",
		label: "Select is complete",
		multiple: false,
		data_list: [
			{
				label: "Complete",
				value: "complete",
			},
			{
				label: "Incomplete",
				value: "incomplete",
			},
		],
		value: "",
	},
];
