import SearchBar from "../components/SearchBar";

export default function SearchBarSection({ isTall = true, initialValue = "" }) {
	return (
		<div
			className="w-full relative bg-black transition-all ease-in-out duration-300"
			style={{ height: isTall ? "calc(100vh - 5rem)" : "24rem" }}
		>
			<img
				src="/wallpaper.jpeg"
				alt="Wallpaper"
				className="w-full h-full object-cover"
			/>
			<div
				className="absolute z-10 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 flex flex-col items-center justify-center w-full"
				// style={{ marginTop: "-50px" }}
			>
				{/* <h1 className="text-white text-3xl text-center font-light">
					Réservez votre préstataire de services auto
				</h1> */}
				<SearchBar initialValue={initialValue} />
			</div>
			<div className="absolute inset-0 bg-black bg-opacity-10"></div>
		</div>
	);
}
