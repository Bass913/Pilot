function ImagesGallery({ images }) {
	return (
		<div className="flex max-h-96 gap-2 mt-5">
			<img
				src={images[0]}
				alt="Garage"
				className="w-1/2 object-cover rounded-md cursor-pointer"
			/>
			{images.length > 3 ? (
				<div className="grid grid-cols-2 gap-2 w-1/2">
					{images.slice(1, 4).map((image, index) => (
						<img
							key={index}
							src={image}
							alt="Garage"
							className="w-full object-cover rounded-md mb-2 h-full cursor-pointer"
						/>
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
