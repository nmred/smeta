#include <stdio.h>
#include <stdlib.h>
#include <stdbool.h>

int main(void)
{
	unsigned long *primes = NULL;
	unsigned long trial = 0;
	
	bool found = false;
	size_t total = 0;
	size_t count = 0;
	size_t i = 0;
	
	printf("How many primes would you like - you'll get at least 4? ");
	scanf("%u", &total);
	
	total = total < 4U ? 4U : total;
	
	primes = (unsigned long *)malloc(total * sizeof(unsigned long));
	
	if (primes == NULL) {
		printf("\nNot enough memory.Hasta la Vista, busy.\n");
		return 1;	
	}

	*primes = 2UL;
	*(primes + 1) = 3UL;
	*(primes + 2) = 5UL;
	
	count = 3U;
	trial = 5U;

	while (count < total) {
		trial += 2UL;
		
		for (i = 0; i < count; i++) {
			if (!(found = trial % *(primes + i))) {
				break;	
			}
		}


		if (found) {
			*(primes + count++) = trial;	
		}
	}

	for (i = 0; i < total; i++) {
		if (!(i % 5U)) {
			printf("\n");	
		}
		printf("%12lu", *(primes + i));
	}

	printf("\n");
	return 0;
}
