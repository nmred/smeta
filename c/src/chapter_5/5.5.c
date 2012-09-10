#include <stdio.h>

int main(void)
{
	long a = 1L;
	long b = 2L;
	long c = 3L;
	
	double d = 4.0;
	double e = 5.0;
	double f = 6.0;
	
	printf("A variable of type long occupies %d bytes.", sizeof(long));
	printf("\nHere are the addresses of some variable of type long: ");	
	printf("\nThe address of a is : %p\n The address of b is : %p", &a, &b);
	printf("\nThe address of c is : %p\n", &c);
	printf("\n\nA variable of type double occupies %d bytes.", sizeof(double));
	printf("\nHere are the addresses of some variable of type double: ");
	printf("\nThe address of d is : %p\n The address of e %p", &d, &e);
	printf("\nThe address of f is : %p\n", &f);
	return 0;
}
