<script></script>

<template>
    <section className="py-12 px-4 md:py-16 md:px-10 bg-gray-light">
      <div className="container mx-auto">
        <h2 className="text-3xl font-bold mb-8">Sejarah Perusahaan</h2>
        
        <div className="space-y-6">
          {historyItems.map((item, index) => (
            <div key={index} className="flex items-center space-x-4">
              <div className="flex-shrink-0 h-16 w-16 md:h-20 md:w-20 bg-gray rounded-full flex items-center justify-center text-navy font-bold">
                {item.year}
              </div>
              <div className="flex-1 bg-navy text-white p-4 md:p-6 rounded-lg">
                <p>{item.description}</p>
              </div>
            </div>
          ))}
        </div>

        <div className="mt-12 grid grid-cols-1 md:grid-cols-2 gap-6">
          <div className="text-gray-700">
            <p className="mb-4">
              Sejak berdiri tahun 2007, Kami telah menghasilkan beragam software terbaik dan unggul. Kami telah berhasil menyelesaikan lebih dari 100 project sistem IT baik skala kecil hingga skala besar dan pengalaman BIIS hingga bertahun-tahun dan multi-nasional.
            </p>
            <p>
              Memberikan dampak nyata dan melihat perusahaan pelanggan kami tumbuh berkat sistem yang kami bangun merupakan kebanggaan tersendiri bagi kami untuk terus berkembang dan melakukan yang terbaik.
            </p>
          </div>
          
          <div className="grid grid-cols-2 gap-4">
            <div className="bg-navy text-white rounded-lg p-6 flex flex-col items-center justify-center">
              <div className="text-3xl font-bold">20+</div>
              <div className="text-center">Pegawai</div>
            </div>
            <div className="bg-gray rounded-lg p-6 flex flex-col items-center justify-center">
              <div className="text-3xl font-bold">100+</div>
              <div className="text-center">Proyek</div>
            </div>
            <div className="bg-navy text-white rounded-lg p-6 flex flex-col items-center justify-center">
              <div className="text-3xl font-bold">70+</div>
              <div className="text-center">Pelanggan</div>
            </div>
            <div className="bg-navy text-white rounded-lg p-6 flex flex-col items-center justify-center">
              <div className="text-3xl font-bold">15+ Tahun</div>
              <div className="text-center">Pengalaman</div>
            </div>
          </div>
        </div>
      </div>
    </section>
</template>