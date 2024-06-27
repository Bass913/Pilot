export default function Button({ onClick, children, ...props }) {
	return (
		<button onClick={onClick} {...props}>
			{children}
		</button>
	);
}
