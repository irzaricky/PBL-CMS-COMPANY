<script></script>

<template>
    <section className="py-12 px-4 md:py-16 md:px-10 bg-gray-light">
      <div className="container mx-auto">
        <h2 className="text-3xl font-bold mb-8">Our Team</h2>
        
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          {teamMembers.map((member, index) => (
            <div key={index} className="bg-white border border-gray rounded-lg p-4 text-center">
              <img 
                src={member.image} 
                alt={member.name} 
                className="w-32 h-32 mx-auto mb-4 object-cover rounded-full"
              />
              <h3 className="text-lg font-semibold">{member.name}</h3>
              <p className="text-gray-600">{member.position}</p>
            </div>
          ))}
        </div>
      </div>
    </section>
</template>
