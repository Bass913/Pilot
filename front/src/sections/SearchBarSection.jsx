import SearchBar from "../components/SearchBar";

export default function SearchBarSection({ isTall = true, initialValue = "" }) {
	return (
		<div
			className={`${
				isTall ? "h-screen" : "h-96 transition-height"
			} w-full relative bg-black transition-all ease-in-out duration-300`}
		>
			<img
				src="/wallpaper.jpeg"
				alt="Wallpaper"
				className="w-full h-full object-cover"
			/>
			<div
				className="absolute z-10 w-1/2 max-w-2xl p-5 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2"
				style={{ marginTop: "-50px" }}
			>
				{/* <h1 className="text-white text-3xl font-normal text-center">
					Réservez votre préstataire de services auto
				</h1> */}
				<SearchBar initialValue={initialValue} />
			</div>
			<div className="absolute inset-0 bg-black bg-opacity-50"></div>
		</div>
	);
}
