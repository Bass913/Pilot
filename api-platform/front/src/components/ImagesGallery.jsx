function ImagesGallery({ images }) {
	return (
		<div className="flex max-h-96 gap-2 mt-5 overflow-hidden h-full">
			<img
				src={images[0]}
				alt="Garage"
				className="w-1/2 object-cover rounded-md cursor-pointer"
			/>
			{images.length > 3 ? (
				<div className="grid grid-cols-2 w-1/2 h-full gap-2">
					{images.slice(1, 4).map((image, index) => (
						<div
							key={index}
							className="bg-cover bg-center bg-no-repeat rounded-md cursor-pointer h-full w-full bg-gray-300"
							style={{ backgroundImage: `url('${image}')` }}
						></div>
					))}
					<div className="relative bg-primary-600 text-white flex items-center justify-center rounded-md overflow-hidden cursor-pointer">
						<div
							className="absolute inset-0 bg-cover bg-center bg-no-repeat"
							style={{
								backgroundImage: `url('${images[0]}')`,
								filter: "blur(10px)",
							}}
						></div>
						<div className="absolute inset-0 bg-black opacity-50"></div>
						<p className="z-10 text-center text-sm">
							Voir les {images.length - 3} autres photos
						</p>
					</div>
				</div>
			) : (
				<img
					src={images[1]}
					alt="Garage"
					className="w-1/2 object-cover rounded-md cursor-pointer"
				/>
			)}
		</div>
	);
}

export default ImagesGallery;
