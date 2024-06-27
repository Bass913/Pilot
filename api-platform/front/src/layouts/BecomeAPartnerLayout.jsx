import React from "react";
import DefaultLayout from "./DefaultLayout";

function BecomeAPartnerLayout({ children }) {
	return (
		<DefaultLayout>
			<div className="relative flex min-h-screen flex-1">
				<div className="flex items-center w-full justify-center lg:justify-start lg:ml-20">
					<div className="z-10 bg-white rounded-sm p-10">
						{children}
					</div>
				</div>
				<img
					className="absolute inset-0 h-full w-full object-cover"
					src="/wallpaper.jpeg"
					alt="Pilot"
				/>
			</div>
		</DefaultLayout>
	);
}

export default BecomeAPartnerLayout;
