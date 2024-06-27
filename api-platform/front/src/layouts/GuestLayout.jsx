import React from 'react';

function GuestLayout({ children }) {
    return (
        <div className="flex min-h-screen flex-1">
            {children}
            <div className="relative hidden w-0 flex-1 lg:block">
                <img className="absolute inset-0 h-full w-full object-cover" src="/wallpaper.jpeg" alt="Pilot" />
            </div>
        </div>
    );
}

export default GuestLayout;
